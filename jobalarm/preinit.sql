SET SESSION group_concat_max_len=10000;

SET FOREIGN_KEY_CHECKS=0; 

TRUNCATE jobalarm.user; 
TRUNCATE jobalarm.company; 
TRUNCATE jobalarm.project; 
TRUNCATE jobalarm.survey; 
TRUNCATE jobalarm.contact;
TRUNCATE jobalarm.sms;
TRUNCATE jobalarm.note;

SET FOREIGN_KEY_CHECKS=1;

-- User Table
INSERT INTO jobalarm.user (companyId,username,password,fullname,firstname,
  lastname,email,security,created,status)
  SELECT 
    NULL,
    u.username,
    u.password,
    CONCAT(u.firstName,' ',u.lastName),
    u.firstName,
    u.lastName,
    u.email,
    u.security,
    u.created,
    1 
    FROM walkupscreener.user u;

-- Company Table
INSERT INTO jobalarm.company (name,description,active)
  SELECT 
    c.name,c.description,c.active
    FROM walkupscreener.company c;

-- Project Table
INSERT INTO jobalarm.project (companyId,name,description,active)
  SELECT
    p.companyId,p.name,p.description,p.active
    FROM walkupscreener.project p;

-- Survey Table
INSERT INTO jobalarm.survey (surveyId, projectId, name, config, questions, 
  display, filters, `edit`, sms, securityId, importDate, updateDate, `status`, active)
  SELECT 
    s.surveyId,NULL,s.name,s.configData,s.questions,s.displayView,s.filtersView,
    s.editView, s.smsView, s.secId,s.importedDate,s.lastUpdated,1,s.active
    FROM walkupscreener.survey s;

-- Contact Table
INSERT INTO jobalarm.contact (mobileNum,currentSurveyId, currentQuestion, 
  currentQuestionTime, firstName, lastName, email, zipCode, createDate, 
  updateDate, status, active)
  SELECT
    p.mobileNum,p.currentSurvey,p.currentQuestion,p.currentQuestionTime,
    pd.firstName,pd.lastName,pd.email,pd.zipCode,p.createDate,p.lastUpdate,
    p.status,p.active
  FROM walkupscreener.people p
    LEFT JOIN walkupscreener.people_data pd
    ON pd.personId = p.id;

-- Sms Table
INSERT INTO jobalarm.sms (surveyId,contactId,senderId,msgDate,mobileNum,msg,
  type,reply,viewed,active)
  SELECT
    sh.surveyId,sh.peopleId,sh.userId,sh.messageDate,sh.mobileNum,
    sh.message,sh.type,sh.isReply,sh.viewed,sh.active
    FROM walkupscreener.sms_history sh;

-- Note Table
INSERT INTO jobalarm.note (surveyId,contactId,mobileNum,userId,noteBody,
  noteDate,noteType,active)
  SELECT
    n.surveyId,n.personId,
    (SELECT c.mobileNum FROM jobalarm.contact c WHERE c.id = n.personId),
    n.userId,n.noteBody,n.noteDate,n.noteType,n.active
    FROM walkupscreener.note n;

delimiter //

-- drop function if exists getColumns;

-- CREATE function getColumns(surveyId varchar(255))
--   RETURNS longtext
-- deterministic
-- BEGIN
-- DECLARE myvar longtext;
-- SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME SEPARATOR ','),"id,responseId,","") INTO myvar
-- FROM INFORMATION_SCHEMA.COLUMNS
-- WHERE TABLE_SCHEMA = 'walkupscreener' AND TABLE_NAME = CONCAT('survey',surveyId)
-- GROUP BY TABLE_NAME;
-- RETURN myvar;
-- END;

-- Remove/Create the procedure for importing responses to the new table structure
DROP PROCEDURE IF EXISTS importResponses //
CREATE PROCEDURE importResponses()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE table_name CHAR(255);
    DECLARE field_list TEXT;
    DECLARE cur1 CURSOR FOR SELECT s.surveyId FROM walkupscreener.survey s;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cur1;

    MYLOOP: LOOP
        FETCH cur1 INTO table_name;
        IF done THEN
            LEAVE MYLOOP;
        END IF;
		
		-- Remove the old response* table
        SET @SQL = CONCAT("drop table if exists jobalarm.response",table_name);
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

		-- Create the new response* table
		SET @SQL = CONCAT("create table jobalarm.response",table_name," SELECT * FROM (SELECT surveyId,contactId,mobileNum,response,
							responseEdit,responseSMS,searchData,stageId,stageDate,eventId,eventDate,fileUpload,surveyType,viewed,
							createDate,updateDate from jobalarm.response) as response CROSS JOIN walkupscreener.survey",table_name);
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

		-- Move the responseId column to be after the mobileNum column
        SET @SQL = CONCAT("alter table jobalarm.response",table_name," MODIFY COLUMN responseId int(11) AFTER mobileNum");
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

		-- Add in the surveyResponseId,status, and active columns and setup the foreign key restraints
        SET @SQL = CONCAT("alter table jobalarm.response",table_name," ADD COLUMN surveyResponseId VARCHAR(50) AFTER mobileNum, 
							ADD COLUMN status tinyint(2) DEFAULT 1, ADD COLUMN active tinyint(2) DEFAULT 1, ADD CONSTRAINT FK_response",
							table_name,"_survey_surveyId FOREIGN KEY (surveyId) REFERENCES survey(surveyId),  ADD CONSTRAINT FK_response",
							table_name,"_contact_id FOREIGN KEY (contactId) REFERENCES contact(id)");
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

		-- Setup our primary key for new response* table
        SET @SQL = CONCAT("alter table jobalarm.response",table_name," add primary key (id)");
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

        SET @SQL = CONCAT("alter table jobalarm.response",table_name," MODIFY COLUMN id int(11) AUTO_INCREMENT FIRST");
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

        SET @SQL = CONCAT("insert into jobalarm.response",table_name," 
          (surveyId,contactId,mobileNum,surveyResponseId,responseId,response,responseEdit,
          responseSMS,searchData,stageId,stageDate,eventId,eventDate,fileUpload,surveyType,
          viewed,createDate,updateDate) 
          SELECT 
             r.surveyId,r.peopleId,r.mobileNum,NULL,r.surveyResponseId,r.response,r.responseEdit,
          r.responseSMS,r.searchData,r.stage,r.stageDate,r.event,r.eventDate,NULL,r.type,
          r.viewed,r.created,r.updated
          FROM walkupscreener.response r WHERE r.surveyId=",table_name);
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

        SET @SQL = CONCAT("insert into jobalarm.response",table_name," 
          (surveyId,contactId,mobileNum,surveyResponseId,responseId,response,responseEdit,
          responseSMS,searchData,stageId,stageDate,eventId,eventDate,fileUpload,surveyType,
          viewed,createDate,updateDate) 
          SELECT 
             r.surveyId,r.peopleId,r.mobileNum,NULL,r.surveyResponseId,r.response,r.responseEdit,
          r.responseSMS,r.searchData,r.stage,r.stageDate,r.event,r.eventDate,NULL,r.type,
          r.viewed,r.created,r.updated
          FROM walkupscreener.response r WHERE r.surveyId=",table_name);
        PREPARE stmt FROM @SQL;
        EXECUTE stmt;
        DROP PREPARE stmt;

        SET @SQL = CONCAT("insert into response",table_name," select ",@FIELD_LIST," from walkupscreener.survey",table_name);

        PREPARE stmt FROM @SQL;

        EXECUTE stmt;
        DROP PREPARE stmt;

    END LOOP;

    CLOSE cur1;
END //

delimiter ;

CALL importResponses();
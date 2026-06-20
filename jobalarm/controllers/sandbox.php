<?php

class Sandbox {
    public static function fixJobCount() {
    
        $dbData = Config::get('db')->get_results("select id from job");
        foreach($dbData as $job) {
            $candidateCount = Company::getJobCandidateCount($job['id']);
            $data = array('num_candidates'=>$candidateCount);
            $where = array('id'=>$job['id']);
            Config::get('db')->update('job',$data,$where);
            echo $job['id']." : ".$candidateCount."<br />\r\n";
        }
        
    }
    
    public static function importCDL() {
    
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8026061779','email'=>'RICHARDAFOLABI25@YAHOO.COM','firstName'=>'AFOLABI','lastName'=>'SUNDAY','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4077909209','email'=>'Alcimett@aol.com','firstName'=>'Alejandro','lastName'=>'Cimetiere','zipCode'=>'33897','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7749926156','email'=>'alejandrosalas63@yahoo.com','firstName'=>'alejandro','lastName'=>'salas','zipCode'=>'02740','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7039466538','email'=>'acolmes1@yahoo.com','firstName'=>'Alisha','lastName'=>'Colmes','zipCode'=>'22407','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7664441823','email'=>'Amanisdiaznoriega@gmail.com','firstName'=>'Amanis','lastName'=>'','zipCode'=>'33010','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9784271671','email'=>'911mckenzie@gmail.com','firstName'=>'Andrew','lastName'=>'McKenzie','zipCode'=>'03873','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9063969055','email'=>'Andy.8952@hotmail.com','firstName'=>'Andy','lastName'=>'Harrell','zipCode'=>'49801','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4235827273','email'=>'','firstName'=>'anthony','lastName'=>'brown','zipCode'=>'37372','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7023313158','email'=>'boltmanbing@yahoo.com','firstName'=>'Anthony','lastName'=>'Wilson','zipCode'=>'89106','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9043387121','email'=>'chho3@aol.com','firstName'=>'antonio','lastName'=>'howatd','zipCode'=>'32218','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9154332139','email'=>'art_macias13@yahoo.com','firstName'=>'Arturo','lastName'=>'Macias','zipCode'=>'79936','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8174714606','email'=>'bd_elliott@live.com','firstName'=>'Billy Joe','lastName'=>'Elliott','zipCode'=>'76137','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3077491477','email'=>'obrbl@aol.com','firstName'=>'blake','lastName'=>'ober','zipCode'=>'82941','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4403822271','email'=>'dodge66@oh.rr.com','firstName'=>'bob','lastName'=>'walker','zipCode'=>'44060','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5123520004','email'=>'CLARK6333@AOL.COM','firstName'=>'BOBBY','lastName'=>'CLARK','zipCode'=>'76574','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4049929535','email'=>'bobby.willbanks@yahoo.com','firstName'=>'Bobby','lastName'=>'Wilbanks','zipCode'=>'30135','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2396343122','email'=>'bearddonald73@gmail.com','firstName'=>'BRIAN','lastName'=>'Beard','zipCode'=>'33907','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2526866434','email'=>'carlosfonville@suddenlink.net','firstName'=>'Carlos','lastName'=>'Fonville','zipCode'=>'28501','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2143990765','email'=>'flowersric29@gmail.com','firstName'=>'Cedric','lastName'=>'Flowers','zipCode'=>'75052','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2524256556','email'=>'cedricterellhawkins@gmail.com','firstName'=>'Cedric','lastName'=>'Hawkins','zipCode'=>'27537','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9187915950','email'=>'mammaswright2@yahoo.com','firstName'=>'celeste','lastName'=>'mccaslin','zipCode'=>'74345','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8176572954','email'=>'rustynut3850@aol.com','firstName'=>'Cheryl','lastName'=>'Walker','zipCode'=>'76023','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6198208268','email'=>'cfreeman1387@gmail.com','firstName'=>'Christopher','lastName'=>'Freeman','zipCode'=>'91977','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3214807624','email'=>'christopherjenkins1969@gmail.com','firstName'=>'Christopher','lastName'=>'Jenkins','zipCode'=>'27610','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9192077581','email'=>'kenion_c07@yahoo.com','firstName'=>'Christopher','lastName'=>'Kenion','zipCode'=>'27863','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5802231224','email'=>'chyrelhall@gmail.com','firstName'=>'chyrel','lastName'=>'hall','zipCode'=>'73401','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9049552057','email'=>'hartsfield.claude@gmail.com','firstName'=>'Claude','lastName'=>'Hartsfield','zipCode'=>'32211','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3092674635','email'=>'craig.reliforf@gmail.com','firstName'=>'Craig','lastName'=>'reliford','zipCode'=>'61571','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8176143075','email'=>'f150mustang82@gmail.com','firstName'=>'Curtis','lastName'=>'Desjardins','zipCode'=>'76006','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8177149321','email'=>'curtissmock@yahoo.com','firstName'=>'curtis','lastName'=>'smock','zipCode'=>'76028','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5025842310','email'=>'dhorn62@hotmail.com','firstName'=>'Dale','lastName'=>'Kielborn','zipCode'=>'40203','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9035304505','email'=>'texas_ribeye@yahoo.com','firstName'=>'dan','lastName'=>'sparkman','zipCode'=>'75773','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7193306102','email'=>'ernadan1950@hotmail.com','firstName'=>'daniel','lastName'=>'Dravland','zipCode'=>'80817','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9544799369','email'=>'daniel28duque@hotmail.com','firstName'=>'daniel','lastName'=>'Londono','zipCode'=>'34953','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5183684601','email'=>'danielpalella@yahoo.com','firstName'=>'danielpalella','lastName'=>'','zipCode'=>'29565','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4045999368','email'=>'dannymccluney0308@yahoo.com','firstName'=>'Danny','lastName'=>'McCluney','zipCode'=>'30318','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3187989357','email'=>'dannymccrary@comcast.net','firstName'=>'Danny','lastName'=>'McCrary','zipCode'=>'71115','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5203050152','email'=>'drpvsd@gmail.com','firstName'=>'Danny','lastName'=>'pesqueira','zipCode'=>'85756','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6148069531','email'=>'darrylw0924@gmail.com','firstName'=>'Darryl','lastName'=>'Williams','zipCode'=>'43211','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4696283667','email'=>'davidbevans2@aol.com','firstName'=>'David','lastName'=>'Evans','zipCode'=>'76118','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2073501505','email'=>'kylemckinney@tidewater.net','firstName'=>'David','lastName'=>'McKnight','zipCode'=>'04543','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4046104478','email'=>'brooksd79@gmail.com','firstName'=>'Demetrius','lastName'=>'Brooks','zipCode'=>'30519','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8322644703','email'=>'Mychalpinkston@gmail.com','firstName'=>'Demitrice','lastName'=>'Pinkston','zipCode'=>'77015','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4014811180','email'=>'dextermcgahee@icloud.com','firstName'=>'Dexter','lastName'=>'Mcgahee','zipCode'=>'28361','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5173982718','email'=>'dbwheaton63@hotmail.com','firstName'=>'Don','lastName'=>'Wheaton','zipCode'=>'49072','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7819742672','email'=>'dfaulkner1981@hotmail.com','firstName'=>'Donal','lastName'=>'Curtin','zipCode'=>'02351','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7794750447','email'=>'d62kelley@yahoo.com','firstName'=>'Donald','lastName'=>'Kelley','zipCode'=>'61080','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8643810103','email'=>'dontahardy86432@gmail.com','firstName'=>'Donta','lastName'=>'hardy','zipCode'=>'29306','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3046402163','email'=>'tincherdoug@gmail.com','firstName'=>'Doug','lastName'=>'Tincher','zipCode'=>'25840','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9198167715','email'=>'Johnsondouglas10@yahoo.com','firstName'=>'Douglas','lastName'=>'Johnson','zipCode'=>'27614','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2089015085','email'=>'','firstName'=>'Doyle','lastName'=>'Narry','zipCode'=>'83705','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7404042811','email'=>'d.erlenbach@live.com','firstName'=>'Duane','lastName'=>'Erlenbach','zipCode'=>'43055','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3617391766','email'=>'esr326@gmail.com','firstName'=>'Eddie','lastName'=>'Reyes','zipCode'=>'78410','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9099100647','email'=>'edercastro69@yahoo.com','firstName'=>'eder','lastName'=>'castro','zipCode'=>'92335','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3373395509','email'=>'waldonpapa@gmail.com','firstName'=>'Edgar','lastName'=>'Waldon','zipCode'=>'70515','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7329009449','email'=>'bigcee67@yahoo.com','firstName'=>'Edward','lastName'=>'Hendrex','zipCode'=>'07724','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7406823083','email'=>'edaleperry54321@gmail.com','firstName'=>'Elliott','lastName'=>'Pery','zipCode'=>'45656','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9545474427','email'=>'Enrique10275@hotmail.com','firstName'=>'Enrique','lastName'=>'','zipCode'=>'33015','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2158392345','email'=>'eslewis9393@gmail.com','firstName'=>'ERIC','lastName'=>'LEWIS','zipCode'=>'19142','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2545410591','email'=>'HONORBOUNDMOWING@YAHOO.COM','firstName'=>'Eric','lastName'=>'Miller','zipCode'=>'76502','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3143274882','email'=>'EugeneJoyce25@gmail.com','firstName'=>'Eugene','lastName'=>'Joyce','zipCode'=>'63136','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7325349999','email'=>'SUSAN111552@AOL.COM','firstName'=>'EVAN','lastName'=>'Dubow','zipCode'=>'08527','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3232087310','email'=>'fjallen198700@gmail.com','firstName'=>'fadil','lastName'=>'allen','zipCode'=>'90262','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4142434302','email'=>'fardpasha84@gmail.com','firstName'=>'fard','lastName'=>'Pasha','zipCode'=>'53206','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3072756981','email'=>'felipehelms@gmail.com','firstName'=>'Felipe','lastName'=>'Helms','zipCode'=>'82082','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6618340610','email'=>'reydetucorazon1@gmail.com','firstName'=>'Fernando','lastName'=>'Carrillo','zipCode'=>'93307','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7863288327','email'=>'frankcarlos123@gmail.com','firstName'=>'frank','lastName'=>'martinez','zipCode'=>'78412','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4014268227','email'=>'FRANKMCFIELD@ROCKETMAIL.COM','firstName'=>'FRANK','lastName'=>'MCFIELD','zipCode'=>'30062','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6097214487','email'=>'garygilbert30@gmail.com','firstName'=>'Gary','lastName'=>'Gilbert','zipCode'=>'07111','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5046384755','email'=>'garythompson4228@gmail.com','firstName'=>'Gary','lastName'=>'Thompson','zipCode'=>'70087','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9783880310','email'=>'geraldtriano@comcast.net','firstName'=>'Gerald','lastName'=>'Triano','zipCode'=>'01913','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4342276104','email'=>'giniraq08@yahoo.com','firstName'=>'Giovonnie','lastName'=>'Cassell','zipCode'=>'22903','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8704841852','email'=>'glenn@firstarkansasfinancial.com','firstName'=>'Glenn','lastName'=>'Strong','zipCode'=>'72150','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2145344638','email'=>'johnson9256@sbcglobal.net','firstName'=>'Harold','lastName'=>'Johnson','zipCode'=>'75202','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2528831859','email'=>'hawathadavis@aol.com','firstName'=>'hawatha','lastName'=>'davis','zipCode'=>'27891','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8632120594','email'=>'lumpkinhenry@gmail.com','firstName'=>'HENRY','lastName'=>'LUMPKIN','zipCode'=>'34638','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8327753751','email'=>'fieldshoward@hotmail.com','firstName'=>'Howard','lastName'=>'Fields, Jr.','zipCode'=>'77086','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8177232564','email'=>'buffinj@peoplepc.com','firstName'=>'J. W.','lastName'=>'Buffin','zipCode'=>'76039','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8779367473','email'=>'empirecartagellc@gmail.com','firstName'=>'jahmal','lastName'=>'Cantero','zipCode'=>'75222','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4012861019','email'=>'xavior22512@gmail.com','firstName'=>'jairon','lastName'=>'encarnacion','zipCode'=>'02909','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8566287277','email'=>'jejones_sr81@yahoo.com','firstName'=>'Jamal','lastName'=>'Jones','zipCode'=>'08066','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2166358445','email'=>'jamalwhitner@yahoo.com','firstName'=>'jamal','lastName'=>'whitner','zipCode'=>'44104','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8189155339','email'=>'james8arm3@yahoo.com','firstName'=>'James','lastName'=>'Armenta','zipCode'=>'93304','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9036642332','email'=>'birdypeep@yahoo.com','firstName'=>'JAMES','lastName'=>'DOUTHIT','zipCode'=>'75488','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9405952277','email'=>'jamiil@creativetransportation.net','firstName'=>'JAMIIL','lastName'=>'HARRIS','zipCode'=>'76227','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3146409895','email'=>'obadiah180@gmail.com','firstName'=>'JASON','lastName'=>'','zipCode'=>'63107','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6237641458','email'=>'Moons518@Hotmail.Com','firstName'=>'Jeff','lastName'=>'Moon','zipCode'=>'85340','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2074022824','email'=>'lilhunter8701@hotmail.com','firstName'=>'jeffrey','lastName'=>'taylor','zipCode'=>'04210','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3092528178','email'=>'robjenndogs@yahoo.com','firstName'=>'Jennifer','lastName'=>'Johnson','zipCode'=>'61422','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9102157948','email'=>'jeramy966@gmail.com','firstName'=>'Jeramy','lastName'=>'Wililliams','zipCode'=>'28387','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5133903977','email'=>'costonjeri@yahoo.com','firstName'=>'Jeri','lastName'=>'Coston','zipCode'=>'45223','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9125483089','email'=>'jimmywilkins45@yahoo.com','firstName'=>'jimmy','lastName'=>'wilkins','zipCode'=>'31501','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8606043485','email'=>'joann1535@live.com','firstName'=>'joann','lastName'=>'cortese','zipCode'=>'06074','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7275659387','email'=>'jhenders0412@gmail.com','firstName'=>'joe','lastName'=>'henderson','zipCode'=>'33706','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8606146543','email'=>'jelluah@gmail.com','firstName'=>'Joel','lastName'=>'','zipCode'=>'06042','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9177427156','email'=>'johnielracing@gmail.com','firstName'=>'johan','lastName'=>'rivera','zipCode'=>'10453','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6468233375','email'=>'j.fusto@yahoo.com','firstName'=>'John','lastName'=>'Fusto','zipCode'=>'94546','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9038244119','email'=>'john_gaskin2000@yahoo.com','firstName'=>'John','lastName'=>'Gaskin','zipCode'=>'75556','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4433077917','email'=>'John.wilkes81@yahoo.com','firstName'=>'Johnnifer','lastName'=>'Wilkes','zipCode'=>'27893','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8048962386','email'=>'jgilliamdw@yahoo.com','firstName'=>'jonathan','lastName'=>'Gilliam','zipCode'=>'23803','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5203133823','email'=>'luna242011@yahoo.com','firstName'=>'jore','lastName'=>'','zipCode'=>'85648','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7872264692','email'=>'cuz_colon@hotmail.com','firstName'=>'Jose','lastName'=>'Cruz','zipCode'=>'00956','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7876024093','email'=>'echevarria2408@gmail.com','firstName'=>'jose','lastName'=>'echevarria','zipCode'=>'34610','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3477571352','email'=>'lpeguero1@optonline.net','firstName'=>'Jose','lastName'=>'Rondon','zipCode'=>'10457','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8302810047','email'=>'joseflosert@gmail.com','firstName'=>'JOSEF','lastName'=>'LOSERT','zipCode'=>'78050','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3364298831','email'=>'Cheeksdriver92@gmail.com','firstName'=>'Joseph','lastName'=>'Cheek','zipCode'=>'27030','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8172717402','email'=>'joeolson12965@yahoo.com','firstName'=>'Joseph','lastName'=>'Olson','zipCode'=>'76140','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5136286494','email'=>'kjohns587@gmail.com','firstName'=>'keith','lastName'=>'johns','zipCode'=>'45002','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5304153322','email'=>'keithnoall@gmail.com','firstName'=>'Keith','lastName'=>'Noall','zipCode'=>'95953','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5704175502','email'=>'turtle-63@hotmail.com','firstName'=>'Kenneth','lastName'=>'Fulk','zipCode'=>'18634','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7758480895','email'=>'gonefishing201059@yahoo.com','firstName'=>'Kevin','lastName'=>'Adams','zipCode'=>'89511','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2147323729','email'=>'kevinkeasler1@yahoo.com','firstName'=>'Kevin','lastName'=>'Keasler','zipCode'=>'75217','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8703562839','email'=>'billykibbey@yahoo.com','firstName'=>'Kibbey','lastName'=>'Billy','zipCode'=>'71933','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8172233602','email'=>'AEROSWINECARTAGE@GMAIL.COM','firstName'=>'KRIS','lastName'=>'WEITKANUT','zipCode'=>'76140','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9192198285','email'=>'lintonburnham@yahoo.com','firstName'=>'linton','lastName'=>'burnham','zipCode'=>'27529','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4148394335','email'=>'lonnellsmith@gmail.com','firstName'=>'Lonnell','lastName'=>'Smith','zipCode'=>'54703','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7869420144','email'=>'luismartin878@yahoo.com','firstName'=>'luis','lastName'=>'martin','zipCode'=>'33144','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8182317194','email'=>'m_rastegar43@yahoo.com','firstName'=>'mahmoud','lastName'=>'rastegar','zipCode'=>'95661','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9738852275','email'=>'mjohnson24872@yhoo.com','firstName'=>'mark','lastName'=>'Johnson','zipCode'=>'07050','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4848329800','email'=>'Markpoker22@yahoo.com','firstName'=>'Mark','lastName'=>'Stevens','zipCode'=>'37830','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8287352543','email'=>'red71961@gmail.com','firstName'=>'maurice','lastName'=>'honeycutt','zipCode'=>'28716','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3619946836','email'=>'melissagalaviz27@gmail.com','firstName'=>'melissa','lastName'=>'galaviz','zipCode'=>'78401','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5128094853','email'=>'mELVINATOR531@AUSTIN.RR.COM','firstName'=>'MELVIN','lastName'=>'FREEMAN','zipCode'=>'78617','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'0734062197','email'=>'elvira@monatic.co.za','firstName'=>'Mervin','lastName'=>'Van Rensburg','zipCode'=>'07925','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3178696453','email'=>'mhunt101@hotmail.com','firstName'=>'michael','lastName'=>'hunter','zipCode'=>'46201','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8603730923','email'=>'mykah05.ml@gmail.com','firstName'=>'Michael','lastName'=>'London','zipCode'=>'06108','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9102289445','email'=>'MPAINTER36@GMAIL.COM','firstName'=>'MICHAEL','lastName'=>'PAINTER','zipCode'=>'28478','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4432864457','email'=>'darmike@comcast.net','firstName'=>'Michael','lastName'=>'Robertson','zipCode'=>'24630','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8014007818','email'=>'streeterthedj@gmail.com','firstName'=>'Michael','lastName'=>'Streeter','zipCode'=>'84663','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9084993649','email'=>'mmorgan45@optonline.net','firstName'=>'Micheal','lastName'=>'Morgan','zipCode'=>'07076','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8622150648','email'=>'bengocheap@aol.com','firstName'=>'miguel','lastName'=>'bengochea','zipCode'=>'07003','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6304410426','email'=>'ruzevich@att.net','firstName'=>'mike','lastName'=>'ruzevich','zipCode'=>'61108','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8172286164','email'=>'Mcdervices01@aol.com','firstName'=>'Mike','lastName'=>'','zipCode'=>'76059','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5154416590','email'=>'mosmos01@live.com','firstName'=>'Mohamud','lastName'=>'Garat','zipCode'=>'50314','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3238986349','email'=>'aegueta56@sbcglobal.nat','firstName'=>'omar','lastName'=>'argueta','zipCode'=>'90018','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6163751389','email'=>'Cervantes.oriel.5@gmail.com','firstName'=>'Oriel','lastName'=>'Cervantes','zipCode'=>'49503','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9199037966','email'=>'pr236900@gmail.com','firstName'=>'paul','lastName'=>'Rankin','zipCode'=>'27545','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9034131801','email'=>'paul.sandlin@sbcglobal.net','firstName'=>'paul','lastName'=>'sandlin','zipCode'=>'75401','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7325981857','email'=>'Petermgunn@yahoo.com','firstName'=>'Peter','lastName'=>'Gunn','zipCode'=>'08527','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4085610768','email'=>'1974homito@gmail.com','firstName'=>'ramiro','lastName'=>'hernandez','zipCode'=>'95121','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4014242889','email'=>'jmdray91@gmail.com','firstName'=>'Ramon.','lastName'=>'rivera','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4064600553','email'=>'whysoftball@yahoo.com','firstName'=>'Randu','lastName'=>'Schreier','zipCode'=>'59101','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7758839074','email'=>'lrico17@sbcglobal.net','firstName'=>'Raul','lastName'=>'Rico','zipCode'=>'89706','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7707229808','email'=>'allproguyette@yahoo.com','firstName'=>'Raymond','lastName'=>'Guyette','zipCode'=>'30680','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9198796352','email'=>'rthanks1969@gmail.com','firstName'=>'reginald','lastName'=>'hanks','zipCode'=>'27520','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6032355555','email'=>'richard.boutin@comcast.net','firstName'=>'Richard','lastName'=>'Boutin','zipCode'=>'03801','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2143940853','email'=>'TOCEWL@HOTMAIL.COM','firstName'=>'Richard','lastName'=>'McArdkle','zipCode'=>'75181','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8034394218','email'=>'richrain@bellsouth.net','firstName'=>'Richard','lastName'=>'Rainwaters','zipCode'=>'29801','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4436315759','email'=>'rtthomas34@gmail.com','firstName'=>'Ricky','lastName'=>'Thomas','zipCode'=>'21117','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3363407192','email'=>'rickyroey@yahoo.com','firstName'=>'Ricky','lastName'=>'','zipCode'=>'27107','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9174970497','email'=>'rgoolcharan40@outlook.com','firstName'=>'riley','lastName'=>'goolcharan','zipCode'=>'11420','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7023453887','email'=>'','firstName'=>'Robert','lastName'=>'Carroll','zipCode'=>'89027','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8596476857','email'=>'janenbruce@yahoo.com','firstName'=>'ROBERT','lastName'=>'Rankin','zipCode'=>'41018','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2252842672','email'=>'robfel720@yahoo.com','firstName'=>'Robert','lastName'=>'','zipCode'=>'70815','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4409831789','email'=>'rgibb01@hotmail.com','firstName'=>'Robert','lastName'=>'','zipCode'=>'44057','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2523147230','email'=>'','firstName'=>'Rodnet','lastName'=>'Cooper','zipCode'=>'37890','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9012335308','email'=>'rodgill03@yahoo.com','firstName'=>'rodney','lastName'=>'gillett','zipCode'=>'33765','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3619465280','email'=>'rudyg446@gmail.com','firstName'=>'rodolfo','lastName'=>'Gonzales','zipCode'=>'78418','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5627268162','email'=>'rudydiaz86@gmail.com','firstName'=>'rodolfo','lastName'=>'','zipCode'=>'90805','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9197270725','email'=>'ronaldclanton@ymail.com','firstName'=>'ronald','lastName'=>'clanton','zipCode'=>'27844','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5044603416','email'=>'rkgre@cox.net','firstName'=>'Ronald','lastName'=>'Greichgauer','zipCode'=>'70001','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2078945338','email'=>'chilleymewilley@roadrunner.com','firstName'=>'Ronald','lastName'=>'Wiley','zipCode'=>'04062','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8704501885','email'=>'Phillipsrusty42@Yahoo.com','firstName'=>'ruaty','lastName'=>'','zipCode'=>'38024','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6825542171','email'=>'muddflap12000@yahoo.com','firstName'=>'RUBIN','lastName'=>'SARABIA','zipCode'=>'76132','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4047319948','email'=>'rudyreed55@yahoo.com','firstName'=>'Rudolph','lastName'=>'Reed','zipCode'=>'30094','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7043007241','email'=>'79Rush@gmail.com','firstName'=>'Rush','lastName'=>'spikes','zipCode'=>'28150','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9085028003','email'=>'Saadmitchell66@gmail.com','firstName'=>'Saad','lastName'=>'Mitchell','zipCode'=>'07111','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7188125436','email'=>'samueltatum17@gmail.com','firstName'=>'samuel','lastName'=>'Tatum','zipCode'=>'27617','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7065059250','email'=>'sanfordparks123@gmail.com','firstName'=>'sanford','lastName'=>'Parks','zipCode'=>'30241','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8039383180','email'=>'','firstName'=>'SCOTT','lastName'=>'KENNETT','zipCode'=>'76082','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3214746174','email'=>'choochoo4471@yahoo.com','firstName'=>'shannan','lastName'=>'watkinson','zipCode'=>'32909','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2199022186','email'=>'simonquintanilla.sq@gmail.com','firstName'=>'Simon','lastName'=>'Quintanilla','zipCode'=>'46324','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7705427579','email'=>'smittydome71@gmail.com','firstName'=>'Stanley','lastName'=>'Smith','zipCode'=>'92104','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7739466675','email'=>'steve.fontaine@ymail.com','firstName'=>'Stephen','lastName'=>'Fontanes','zipCode'=>'46312','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2522133545','email'=>'econogizer@aol.com','firstName'=>'Stephen','lastName'=>'Lee','zipCode'=>'27850','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5599993905','email'=>'leftcoasthoops@comcast.net','firstName'=>'Steve','lastName'=>'Dannemiller','zipCode'=>'93720','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2815932452','email'=>'dlmirrer@hotmail.com','firstName'=>'Steve','lastName'=>'Mirrer','zipCode'=>'77837','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7074507600','email'=>'steve@duyainc.com','firstName'=>'Steven','lastName'=>'Best','zipCode'=>'95687','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7203505239','email'=>'sv11@COMCAST.NET','firstName'=>'steven','lastName'=>'','zipCode'=>'80002','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9105200620','email'=>'firstboxer@yahoo.com','firstName'=>'Terry','lastName'=>'Robertson','zipCode'=>'28429','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5044165760','email'=>'thamelussmith@yahoo.com','firstName'=>'thamelus','lastName'=>'smith','zipCode'=>'70068','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7755774989','email'=>'9mainz@att.net','firstName'=>'Thomas','lastName'=>'Mainz','zipCode'=>'89429','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4174250436','email'=>'tomraderjr@gmail.com','firstName'=>'thomas','lastName'=>'Rader','zipCode'=>'65803','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2074234429','email'=>'tgagne63@hotmail.com','firstName'=>'tim','lastName'=>'gagne','zipCode'=>'04046','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8129458615','email'=>'timjschepers@hotmail.com','firstName'=>'Tim','lastName'=>'Schepers','zipCode'=>'47150','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2674212081','email'=>'tjldbaum@yahoo.com','firstName'=>'Timothy','lastName'=>'Baum','zipCode'=>'18969','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9418155018','email'=>'vicbat11@gmail.com','firstName'=>'Victor','lastName'=>'Batista','zipCode'=>'33947','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6617295798','email'=>'mcgonagill.vince@yahoo.com','firstName'=>'vince','lastName'=>'mcgonagill','zipCode'=>'93535','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8138173000','email'=>'waltermartinez69@yahoo.com','firstName'=>'walter','lastName'=>'martinez','zipCode'=>'34608','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5743778872','email'=>'hotrod_casper4u@yahoo.com','firstName'=>'Wendy','lastName'=>'heeter','zipCode'=>'46580','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8172402606','email'=>'shelton.william@sbcglobal.net','firstName'=>'William','lastName'=>'Shelton','zipCode'=>'76645','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8566071059','email'=>'goodoctor13@yahoo.com','firstName'=>'Willie','lastName'=>'Benton','zipCode'=>'08021','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2052408191','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2052709938','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2143990765','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2252007133','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2294491821','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2488426411','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2567054224','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2673455752','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3024489167','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3093738200','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3238986349','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3363674767','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'3393099932','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4058388213','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4106990483','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4433077917','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'4848329800','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5042363794','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5089678033','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5177033955','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5639213344','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5734241967','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'5803717086','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'6034759085','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7015805509','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7172179624','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7274245279','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7275659387','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7608344053','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7702561626','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7707229808','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7708234015','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7817925000','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7818372411','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7862968098','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7863878248','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'7864441823','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8046255955','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8282269466','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8312627863','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8322644703','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8456651199','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8596208597','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'8606146543','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9063969055','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9099005822','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9547096564','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9703014740','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9729984605','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'9738852275','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
        $blank = Response::generateBlank(34478,array('mobileNum'=>'2038098746','email'=>'','firstName'=>'','lastName'=>'','zipCode'=>'','position'=>'Truck Driver'));
        $responseId = Response::add(34478,$blank, true);
    }
    
    public static function fixDownloads() {
        Config::set('fixer',true);
        $surveyList = Config::get('db')->get_results('select surveyId from survey where surveyId != 127884');
        foreach($surveyList as $survey) {
            $surveyId = $survey['surveyId'];
            Survey::load($surveyId);
            $uploadField = (isset(Survey::$_configData[STATIC_VARS::SURVEYFILE])) ? Survey::$_configData[STATIC_VARS::SURVEYFILE] : null;
            if ($uploadField) {
                $dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE responseFile = '' and surveyId = {$surveyId} order by id desc");
                foreach($dbData as $response) {
                    echo $response['surveyResponseId']."<br />";
                    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
                    $static = Response::getStaticFieldsFromData($surveyId,$answers);
                    if (isset($static['upload']) && strlen($static['upload']) > 0) {
                        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],$uploadField,basename($static['upload']));
                    }
                }
            }
        }
    }
    
    public static function fixOptOut() {
    }
    
    public static function getGlobalTest() {
    
        Response::getGlobalTest();
    
    }
    
    public static function testDefaultPost() {
    
        echo Survey::getDefaultPostID(28421);
        
    }
    
    public static function testCount() {
        $count = 0;
        $details = User::getJobList(3,0,1,$count);
        var_dump($count);
    }
    
    public static function testPostCount() {
    
        echo Company::getJobCandidateCount(220);
        
    }
    
    public static function testDefaultAdmin() {
    
        $data = User::getDefaultAdminSurveyList(1);
        
        var_dump($data);
        
    }
    
    public static function fixTables() {
    
        $query = "select surveyId from survey where active>0";
        $surveyData = Config::get('db')->get_results($query);
        foreach($surveyData as $survey) {
            echo "<H1>".$survey['surveyId']."</H1>";
            $query = "show columns from survey".$survey['surveyId'];
            $dbData = Config::get('db')->get_results($query);
            foreach($dbData as $column) {
                if (strpos($column['Field'],'Display') > 0) {
                    var_dump($column);
                    if (substr($column['Type'],0,7) == 'varchar') {
                        $query = "
                        ALTER TABLE survey{$survey['surveyId']}
                        MODIFY {$column['Field']} TEXT;                                       
                    ";
                        Config::get('db')->query($query);
                    }
                    
                }
            }
        }
        
    }
    
    public static function smsExists($mobileNum,$message) {
        
        $query = "select id from sms_history where mobileNum='{$mobileNum}' and message='{$message}'";
        $dbData = Config::get('db')->get_results($query);
        return (count($dbData) > 0);
        
    }
    
    public static function addSms($mobileNum,$message,$date) {
        $person = Person::read($mobileNum,true);
        if ($person) {
            $surveyId = Person::getCurrentSurvey();
            if ($surveyId > 0) {
                $msgDate = DateTime::createFromFormat('m-d-Y g:i A', $date);
                
                $data = array(
                    'mobileNum' => $mobileNum,
                    'message'=>Config::get('db')->filter($message),
                    'surveyId'=>$surveyId,
                    'peopleId'=>$person['id'],
                    'messageDate'=> $msgDate->format('Y-m-d H:i:s'),
                    'type'=>3,
                    'isReply'=>0

                );
                
                Config::get('db')->insert('sms_history',$data);
                
            } 

        }
        
    }
    
    
    public static function importSent() {
        $file = fopen("dat/temp/sent_messages.csv","r");
        $i = 0;
        while(!feof($file)) {
            $data = fgetcsv($file);
            if (strlen(trim($data[1])) > 0) { 
                if (strlen($data[0]) == 10) {
                    if (!self::smsExists($data[0],$data[1])) {
                       self::addSms($data[0], $data[1], $data[2]);
                        $i++;
                    }
                } else 
                    if (strlen($data[0]) > 10) {
                        $numList = explode(',',$data[0]);
                        if (count($numList) > 0) {
                            foreach($numList as $k=>$num) {
                                $number = trim($num);
                                if (!self::smsExists($number,$data[1])) {
                                    self::addSms($number, $data[1], $data[2]);
                                    $i++;
                                }                        
                            }
                        }
                    }
            }
        }        
        echo $i;
    }
    
    
    public static function syncSMSHistoryToUsers() {

        $query = "select surveyId from survey where active >0";

        $dbData = Config::get('db')->get_results($query);

        foreach($dbData as $survey) {
            echo "<hr /><br />".$survey['surveyId']."<br /><br />";
            $subQueryA = "
                SELECT id, userId, mobileNum,messageDate, type, isReply
                from sms_history
                WHERE
                ((type=2) OR (type=1 AND isReply = 1))
                AND surveyId={$survey['surveyId']}
                AND active = 1
                
                order by mobileNum asc, messageDate asc
            ";

            echo "<br />".$subQueryA."<br /><br />";
            $subDBDataA = Config::get('db')->get_results($subQueryA);
            $currentUserId = 0;
            $currentMobileNum = '';
            foreach($subDBDataA as $sms) {
                var_dump($sms);
                if ($currentMobileNum != $sms['mobileNum']) {
                    echo "<br />didn't equal mobileNum<br /><br />";
                    //if we are on a new mobileNumber lets reset
                    $currentMobileNum = $sms['mobileNum'];
                    $currentUserId = 0;
                    
                }
                
                if ($sms['type'] == 2) {
                    
                    $currentUserId = $sms['userId'];
                    echo "<br />hit type 2 : currentUserId: {$currentUserId}<br /><br />";
                    
                } else {
                    
                    if ($sms['isReply'] == 1 && $currentUserId > 0) {
                        echo "<br />setting Data : currentUserId: {$currentUserId}<br /><br />";
                        $data = array('userId' => $currentUserId);
                        $where = array('id'=>$sms['id'],'surveyId'=>$survey['surveyId'],'mobileNum'=>$currentMobileNum);
                        
                        Config::get('db')->update('sms_history',$data,$where);

                        $query = "update sms_history set userId={$currentUserId} where and surveyId={$survey['surveyId']} and mobileNum='{$currentMobileNum}' userId=0 and type=1 and isReply = 1 and messageDate < '{$sms['messageDate']}'";
                        Config::get('db')->query($query);
                    }
                }
            }


        }

        return true;
    }
    
    public static function updateCompanies() {
    
        $query = "update note n set companyId = (select companyId from user u where n.userId=u.id)";
        
    }
    
    
    
    public static function testCompany() {
    
        $user = User::load(1);
        $company = $user['companyId'];
        
        $notes = NoteManager::getNotesByResponse(20734157);
        echo json_encode($notes);
    
    }
    
    public static function testPos() {
    
        $surveyId = 43542; 
        $responseId = 24014245;
        
        Survey::load($surveyId);
        
        $staticVars = Survey::getStaticVars($surveyId);        
        
        $response = Response::getResponseAnswers($surveyId,$responseId);

        $staticData = Response::getStaticFieldsFromData($surveyId,$response);

        var_dump(Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position']]['label']);
//        var_dump($staticData);
        
        
    }

    public static function testFix() {
        Response::updateSec(28865918);
    }
    
    public static function fixResponses() {
    
        $query = "select surveyResponseId from response where created > '2014-10-21' and created < '2014-11-21' and responseSMS = '' order by created desc";
        $dbData = Config::get('db')->get_results($query);
        
        foreach($dbData as $response) {
            //echo $response['surveyResponseId']."<br />";
            Response::updateSec($response['surveyResponseId'],false);
            
        }
    
    }
    
    public static function sms() {
        $data = array(
          'User'          => EZ_LOGIN,
          'Password'      => EZ_PASSWORD,
          'PhoneNumbers'  => array('2149343360'),
          'Message'       => 'test'
          );
        
        $curl = curl_init('https://app.eztexting.com/sending/messages?format=json');
        var_dump($curl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // If you experience SSL issues, perhaps due to an outdated SSL cert
        // on your own server, try uncommenting the line below
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        echo $response;
        curl_close($curl);
    
    }
    
    public static function retroFixOptOut($surveyId=0) {
        
        set_time_limit(0);
        
        $query = "select surveyId,peopleId,surveyResponseId,response,mobileNum from response where created>'2014-12-01'";
        
        $dbData = Config::get('db')->get_results($query);
        
        foreach($dbData as $response) {
            $surveyId = $response['surveyId'];
            $responseData = json_decode($response['response'],true);
                
            //$personId=$response['peopleId'];
            $dataArray = Response::getStaticFieldsFromData($surveyId, $responseData);
            var_dump($dataArray);
            echo "<br />";
            if (isset($dataArray['optin'])) {
                echo $response['peopleId'].' '.$response['surveyResponseId']." : ";
                var_dump($dataArray['optin']);
                echo"<br />";
                if (isset($dataArray['optin']) && is_array($dataArray['optin']) && $dataArray['optin'][0] == 1) {
                    Person::optOut($response['mobileNum']);
                    echo '<span style="color:red;font-weight:bold"> Opted Out</span>';
                } else {
                    Person::optIn($response['mobileNum']);
                    echo '<span style="color:green;font-weight:bold"> Opted In</span>';
                }
            } else {
                echo $response['peopleId'].' '.$response['mobileNum'].' Opted back in<br />';
                Person::optIn($response['mobileNum']);
            }
        }
        
        
    }
    
    
    public static function fixGlobalSearchData() {
        set_time_limit(0);
        $query = "select surveyId,surveyResponseId from response where surveyId>1 and surveyResponseId>1 limit 9000,1000";
        $dbData = Config::get('db')->get_results($query);
        foreach($dbData as $response) {
            Response::updateSearch($response['surveyId'],$response['surveyResponseId']);
        }
    }
    
    public static function smsToResponse($surveyId=null) {
        $survey = Survey::load($surveyId);
        $query = "SELECT distinct mobileNum,messageDate FROM jawalkup.sms_history where surveyId={$surveyId} and isReply = 0 and type=1";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            foreach($dbData as $sms) {
                $data = array(
                    'mobileNum' => $sms['mobileNum'],
                    '_updated_at' => $sms['messageDate'],
                    '_created_at' => $sms['messageDate']
                );
                echo $sms['mobileNum'].": ";
                $blankResponse = Response::generateBlank($surveyId, $data);

                $responseId = Response::add($surveyId, $blankResponse, true);
                echo $responseId."<br /><br />";
            }
        }
    }

    public static function fixDatesSmsResponse($surveyId=null) {
        if ($surveyId) {
            $query = "SELECT distinct mobileNum,messageDate FROM jawalkup.sms_history where surveyId={$surveyId} and isReply = 0 and type=1";
            $dbData = Config::get('db')->get_results($query);
            if ($dbData && count($dbData) > 0) {
                foreach($dbData as $sms) {
                    echo $sms['mobileNum']."<br />";
                    $mobileNum = $sms['mobileNum'];
                    $messageDate=$sms['messageDate'];
                    $query = "update response set updated='{$messageDate}',created='{$messageDate}' where surveyId={$surveyId} AND mobileNum='{$mobileNum}'";
                    Config::get('db')->query($query);
                    $query = "update survey{$surveyId} set TMPTBL_updated_at='{$messageDate}',TMPTBLDisplay_updated_at='{$messageDate}',TMPTBL_created_at='{$messageDate}',TMPTBLDisplay_created_at='{$messageDate}' where TMPTBLDisplaypve8ExCaeG_2='{$mobileNum}'";
                    Config::get('db')->query($query);
                }
            }
        }
    }

    public static function testFixSearch() {
        Response::updateSearch(28421,11504);
    }
    
    public static function testVoice() {

        $options['numbers'] = array(array('mobileNum' => '2149343360'));
        $options['wavfile'] = 'outputfile.wav';
        $result = SmsManager::sendVoiceSMS($options);
        echo $result;
    }

    public static function testAccess() {
        User::addSurveyAccess(1, 1);
        print_r(User::getSurveyAccessArray(1));
        User::addSurveyAccess(1, array(2, 3, 4));
        echo "<br /><br />";
        print_r(User::getSurveyAccessArray(1));
        echo "<br /><br />";
        print_r(User::getSurveyAccessString(1));
        echo "<br /><br />";
        User::remSurveyAccess(1, 3);
        print_r(User::getSurveyAccessString(1));
        echo "<br /><br />";
        User::remSurveyAccess(1, array(1, 4));
        print_r(User::getSurveyAccessString(1));
    }

    public static function testHold() {
        $mobileNum = '2149343360';
        $message = "i'm dvg";

        if (SmsManager::isDrivingMessage($message)) {
            echo "is driving!";
        }
        //Person::setHold($mobileNum);
        //$onHold = Person::onHold($mobileNum);
        //var_dump($onHold);
        //Person::removeExpiredHolds();
        //$onHold = Person::onHold($mobileNum);
        //var_dump($onHold);
    }

    public static function import() {
        Survey::importMissingLiveSurveys();
    }

    public static function testRef() {
        $survey = Survey::read(28423);
        var_dump($survey);
        //$refList = Survey::getSurveyRefKeywordList();
        ////var_dump($refList);
        //if (array_key_exists('asdf',$refList)) {
        //    echo "found surveyId: ".$refList['asdf'];
        //}
    }

    public static function buildKeywordQuery($outvar, $keywords) {
        $keywords = strtolower($keywords);
        $buildArray = array();
        $outString = "";
        $orArray = explode(" or ", $keywords);
        foreach ($orArray as $subs) {
            $tempSubs = explode(" and ", $subs);
            $outString = "(";
            $subArray = array();
            foreach ($tempSubs as $word) {
                $comparator = 'LIKE';
                if (substr($word, 0, 1) == '-') {
                    $comparator = 'NOT LIKE';
                    $word = substr($word, 1);
                }
                $subArray[] = "{$outvar} {$comparator} '%{$word}%'";
            }
            $outString .= implode(" AND ", $subArray);
            $outString .= ")";
            $buildArray[] = $outString;
        }

        return "(" . implode(" OR ", $buildArray) . ")";
    }

    private static function arrayRecursiveDiff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = self::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }

    public static function detect() {
        $dbSurveys = Survey::getAllDBSurveys();
        $liveSurveys = Survey::getAllLiveSurveys();
        $liveArray = array();
        foreach ($liveSurveys as $liveSurvey) {
            $liveArray[$liveSurvey->getId()] = $liveSurvey;
        }
        //var_dump($liveArray);
        foreach ($dbSurveys as $dbSurvey) {
            if ($dbSurvey['lastUpdated'] != $liveArray[$dbSurvey['surveyId']]->getLastUpdate()) {
                echo "<h1>change detected in survey" . $dbSurvey['surveyId'] . "</h1>";
                echo $dbSurvey['lastUpdated'];
                echo " - " . $liveArray[$dbSurvey['surveyId']]->getLastUpdate();
                echo "<br /><br />";
                $liveSurveyDetails = Survey::getLiveSurveyDetails($dbSurvey['surveyId']);
                $liveSurveyQuestions = $liveSurveyDetails['variables'];
                $dbSurveyQuestions = json_decode($dbSurvey['questions'], true);

                //compare question data
                $diff = self::arrayRecursiveDiff($liveSurveyQuestions, $dbSurveyQuestions);
                var_dump($diff);
                ////find fields that exist in the live survey but not the database survey
                //foreach($liveSurveyQuestions as $k => $v) {
                //    if (!array_key_exists($k,$dbSurveyQuestions)) {
                //        echo $k."<br />";
                //    }
                //}
                //echo "<br />";
                ////find fields that exist in the database survey but not the live survey
                //foreach($dbSurveyQuestions as $k => $v) {
                //    if (!array_key_exists($k,$liveSurveyQuestions)) {
                //        echo $k."<br />";
                //    }
                //}
                //echo "<br />";
                ////compare other survey details
                //if ($liveArray[$dbSurvey['surveyId']]->getName() != $dbSurvey['name']) {
                //    echo "NAME DIFFERENT<br />";
                //    echo "LIVE: ".$liveArray[$dbSurvey['surveyId']]->getName()."<br />";
                //    echo "DB:   ".$dbSurvey['name'];
                //}
                //echo "<br />";
            }
        }
        //var_dump($dbSurveys);
        //var_dump($liveSurveys);
    }

    public static function run() {

        set_time_limit(0);


        $keywords = "blah and -bleh or whee and yeah and mofo or else";
        $filterstr = self::buildKeywordQuery("response.searchData", $keywords);
        echo $filterstr;

        //Survey::importMissingLiveSurveys();
        //Response::importNewResponses(65310);
        //var_dump(Person::exists('7197788900'));
        //$person = Person::read('7197788900',true);
        ///var_dump($person);
        //$timer = time();
        //echo Utility::getPHPExecutableFromPath();
        //$response = json_decode('{"gdqGB1IDTV":"Welder","_locale":298,"_updated_at":"2014-04-30T08:44:41-05:00","pve8ExCaeG_4":"74601","_key":"c9f40a1d7ff23a133bb87f4086d0e8cec12dc3d4","_completion_time":"00:02:25","_ip_address":"166.137.156.35","_created_at":"2014-04-30T08:42:16-05:00","_weighted_score":1,"_referrer":"http:\/\/surveys.walkupscreener.com\/s\/welder\/","_completed":0,"_pagepath":[1,2,4],"pve8ExCaeG_2":"2145550102","_language":"en","pve8ExCaeG_0":"Trey","pve8ExCaeG_1":"Cloud","_id":90000002,"o1ouF4GEra":[0],"pve8ExCaeG_3":"Platinumjewel_984@yahoo.com"}',true);
        //$data = array(
        //    'responseId' => 9000002,
        //    'response' => $response
        //    );
        //echo Response::createSec(28421,$data);
        //$questionInsertFields = Survey::getQuestionInsertFields(28421);
        //echo Utility::createArrayKeyCSV($questionInsertFields);
        //echo "<Br />";
        //echo "<Br />";
        //echo "<Br />";
        //echo Utility::createArrayValCSV($questionInsertFields);
        //$command = PHP_EXECUTABLE." {$_SERVER['DOCUMENT_ROOT']}/process.php 65310";
        //echo $command."<br />";
        //Utility::execInBackground($command);
        //echo "Processing begins";

        $surveyId = 28421;
        $responseId = 12844251;
        //include "lib/class.rtf2text.php";
        //echo rtf2text('dat\surveyfiles\28421\7046682055\chris resume.rtf');
        //Survey::load($surveyId);
        //echo Survey::$_questions['T5O0GvH9CX']->getAnswerText(0);
        //$query = "select * from response left join people on response.peopleId = people.id where people.firstName = '' and surveyResponseId > 100000 and responseSMS=''";
        //$result = Config::get('db')->get_results($query);
        //foreach($result as $response) {
        //    echo $response['surveyResponseId']."<br />";
        //    Person::updateFromResponse($response['surveyId'],$response['peopleId'],$response['surveyResponseId'],RESPONSE_TYPES::RESPONSE);
        //}
        //$docObj = new DocxConversion('dat\surveyfiles\28421\5757030259\Resume.doc');
        //$docObj = new DocxConversion("test.docx");
        //$docObj = new DocxConversion("test.xlsx");
        //$docObj = new DocxConversion("test.pptx");
        //echo $docText= $docObj->convertToText();
        //$ext = pathinfo('Kenya_Jenkins.pdf', PATHINFO_EXTENSION);
        //echo $ext."<br />";
        //include('lib/class.pdf2text.php');
        //$a = new PDF2Text();
        //$a->setFilename('dat\surveyfiles\28421\9037204640\Kenya_Jenkins.pdf');
        //$a->decodePDF();
        //echo $a->output();
        //SmsManager::optOut('2149343360');
        //$optOut = SmsManager::isOptOut('2149343360');
        //var_dump($optOut);
        //echo Response::getLastUpdateSurvey($surveyId);
        //$stages = Stage::getName(1);
        //var_dump($stages);
        //Survey::load($surveyId);
        //$query = "
        //    SELECT id,surveyId,surveyResponseId,response FROM response WHERE surveyResponseId < 100000 AND response != '' AND responseSMS = ''
        //";
        //$dbData = Config::get('db')->get_results($query);
        //foreach($dbData as $response) {
        //    $responseSysId = $response['id'];
        //    $surveyId = $response['surveyId'];
        //    $responseId = $response['surveyResponseId'];
        //    $response = $response['response'];
        //    $responseSecTable = "survey".$surveyId;
        //    $parsedResponse = json_decode($response,true);
        //    if (is_array($parsedResponse) && isset($parsedResponse['_id']) && $parsedResponse['_id'] != '') {
        //        $fluidResponseId = $parsedResponse['_id'];
        //        if ($responseId != $fluidResponseId)                 {
        //            echo "found some BS: {$responseId}   ---   {$fluidResponseId}<br /><br />\r\n\r\n";
        //            $updateDate = array('surveyResponseId'=>$fluidResponseId);
        //            $updateWhere = array('surveyResponseId'=>$responseId);
        //            Config::get('db')->update('response',$updateDate,$updateWhere,1);
        //            $updateDateB = array('responseId'=>$fluidResponseId);
        //            $updateWhereB = array('responseId'=>$responseId);
        //            Config::get('db')->update($responseSecTable,$updateDateB,$updateWhereB,1);
        //        }
        //    }
        //}
        //$surveyId = 28426;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        //$surveyId = 65310;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        //$surveyId = 66499;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        //$surveyId = 46709;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        //$surveyId = 54000;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        //$surveyId = 54869;
        //$staticVars = Survey::getStaticVars($surveyId);
        //$dbData = Config::get('db')->get_results("SELECT surveyResponseId,mobileNum FROM response WHERE surveyId = {$surveyId} order by id desc");
        //foreach($dbData as $response) {
        //    $answers = Response::getResponseAnswers($surveyId,$response['surveyResponseId']);
        //    $static = Response::getStaticFieldsFromData($surveyId,$answers);
        //    if (isset($static['upload']) && strlen($static['upload']) > 0) {
        //        Response::downloadFile($surveyId,$response['surveyResponseId'],$response['mobileNum'],Survey::$_configData[STATIC_VARS::SURVEYFILE],basename($static['upload']));
        //    }
        //}
        
        $surveyId = 28421;

        //SmsManager::receive('2149343360','weld',array('key'=>'weld'));
        //SmsManager::receive('2149343360','text',array('reply'=>1));
        //SmsManager::receive('2149343360','John',array('reply'=>1));
        //        SmsManager::receive('2149343360','McClelland',array('reply'=>1));
        //SmsManager::receive('2149343360','75043',array('reply'=>1));
        //echo Survey::getSurveyEmail($responseId,$surveyId,'test');
        //Person::updateFromResponse($surveyId,1771,$responseId,RESPONSE_TYPES::RESPONSEEDIT);
        //$keywordList = Survey::getSurveyKeywordList();
        //var_dump($keywordList);
        //Survey::sendEmail(User::getId(),$surveyId,$responseId,"John McClelland","setzor@gmail.com","","hey buddy");
        //$staticVars = Survey::getStaticVars($surveyId);
        //var_dump($staticVars);
        //$data = Response::getResponseAnswers($surveyId,$responseId);
        //var_dump($data);
        //Survey::load($surveyId);
        /*
        $response = Response::readPri($surveyId,$responseId);
        $responseData = json_decode($response['response'],true);
        $dataArray = Response::getStaticFieldsFromData($surveyId,$responseData);
        var_dump($dataArray);
         */


        //Response::importNewResponses($surveyId);
        //$dbData = 1;
        //while (count($dbData) > 0) {
        //    $query = "SELECT * FROM responsequeue WHERE surveyId={$surveyId} AND processed=0 LIMIT 0,100";
        //    $dbData = Config::get('db')->get_results($query);
        //    if ($dbData && count($dbData) > 0) {
        //        foreach($dbData as $response) {
        //            $responseData = json_decode($response['responseData'],true);
        //            //var_dump($responseData);
        //            echo "<hr />";
        //            Response::add($surveyId,$responseData,false);
        //            Response::queueMarkProcessed($response['responseId']);
        //        }
        //    }
        //}
        //$answers = Response::getAnswers($surveyId);
        //var_dump($answers);
        //$responses = Response::importNewResponses(28421);
        //echo $responses."<br />";
        //echo $responses['totalCount']."<br />";
        //print_r(time()-$timer);
        //$class = new ReflectionClass('Survey');
        //$arr = $class->getStaticProperties();
        //var_dump($arr);
        //$liveSurveys = Survey::getAllLiveSurveys();
        //var_dump($liveSurveys);
        //var_dump(Survey::$_displayView);
        //echo "<br /><br />";
        //var_dump(Survey::$_filtersView);
        //echo "<br /><br />";
        //var_dump(Survey::$_editView);
        //echo "<br /><br />";
        //var_dump(Survey::$_smsView);
        //$blank = Response::generateBlank(65310,array('mobileNum'=>'2145550000','firstName'=>'John','lastName'=>'McClelland','email'=>'setzor@gmail.com','zipCode'=>'75043','position'=>1,'location'=>2));
        //var_dump($blank);
        //$config = Survey::getConfigData();
        //var_dump($config[STATIC_VARS::MOBILENUM]);
        //$blankTemplate = Survey::getResponseTemplate();
        //var_dump(json_encode($blankTemplate));
        //$query = "select response,responseEdit,responseSMS FROM response where surveyResponseId=6511";
        //$dbData = Config::get('db')->get_results($query);
        //$response = json_decode($dbData[0]['response'],true);
        //$responseEdit = json_decode($dbData[0]['responseEdit'],true);
        //$responseSMS = json_decode($dbData[0]['responseSMS'],true);
        //echo "<br /><br />RESPONSE::<br /><br />";
        //var_dump($response);
        //echo "<br /><br />RESPONSE EDIT::<br /><br />";
        //var_dump($responseEdit);
        //echo "<br /><br />RESPONSE SMS::<br /><br />";
        //var_dump($responseSMS);
        //echo "<br /><br />ANSWERS::<br /><br />";
        //echo "<pre>";
        //Survey::setAnswers($response,$responseSMS,$responseEdit);
        //var_dump(Survey::$_questions);
        //echo "</pre>";
        //$surveys = SurveyAdmin::getAllLiveSurveys();
        //print_r($surveys);
    }

    public static function getSurvey($surveyId) {
        //$survey = SurveyAdmin::getLiveSurveyDetails($surveyId);
        //$survey = SurveyAdmin::getAllLocalSurveys();
        //var_dump($survey);
        //$data = array(
        //    'surveyId'=>$survey->getId(),
        //    'securityId'=>Utility::genCode(40),
        //    'name'=>$survey->getName(),
        //    'rawConfig'=>addslashes($survey->getRaw()),
        //    'lastRawUpdate'=>date('Y-m-d H:i:s'),
        //    'lastUpdate'=>$survey->getLastUpdate()
        //    );
        //Survey::create($data);
    }

    public static function importSurveys() {
        //SurveyAdmin::importMissingLiveSurveys();
        //SurveyAdmin::getResponseCount('1');
    }

    public static function teststuff() {
        //SurveyAdmin::
        //$response = Response::createBlank(28421);
        //var_dump($response);
        //echo SmsManager::startSMSSurvey('2149343360');
        //echo "<br />";
        //echo SmsManager::receiveSMSSurveyQuestionAnswer('2149343360','testa');
        //echo "<br />";
        //echo SmsManager::receiveSMSSurveyQuestionAnswer('2149343360','testb');
        //echo "<br />";
        //echo SmsManager::receiveSMSSurveyQuestionAnswer('2149343360','testc');
        //echo "<br />";
        //echo SmsManager::receiveSMSSurveyQuestionAnswer('2149343360','testd');
        //echo "<br />";
        //echo SmsManager::receiveSMSSurveyQuestionAnswer('2149343360','teste');
        //$zips = SurveyAdmin::getDistanceQuery('75218',5);
        //var_dump($zips);
        //$data = Response::existsPri('28421','2149343360');
        //echo $data;
        //echo "<br />";
        //$data = Response::existsSec('28421',$data);
        //echo $data;
    }

    public static function gfn() {
        //SurveyAdmin::downloadResponseFile(65310,12051459,'4694717284','FUbSaRcLSe','Kasandra Gonzales - Resume.doc');
    }

    public static function getAllResponse($responseId) {
        $surveyId = Response::getSurveyId($responseId);
        $personId = Response::getPersonId($responseId);
        $user = User::load(Config::get('loggedIn'));
        
        $query = "SELECT 
            id as recid,
            DATE_FORMAT(messageDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_history
            where surveyId={$surveyId}
            AND peopleId={$personId}
            AND (isReply=1 or (type=2 AND userId={$user['id']}))            
            ORDER BY messageDate DESC
            ";
        $dbData = Config::get('db')->get_results($query);
        foreach ($dbData as $k => $v) {
            $dbData[$k]['smsMsg'] = stripslashes($dbData[$k]['smsMsg']);
            $dbData[$k]['style'] = ($dbData[$k]['smsType'] == 1) ? 'color:blue' : 'color:red';
        }
        $outArray = array(
            'status' => 'success',
            'total' => "" . count($dbData) . "",
            'records' => $dbData
        );
        echo json_encode($outArray);
    }
    
    public static function testsms() {
        
        $sms_history = self::getAllResponse(19395403);
        
        //$john = Person::read('2149343360', true);
        //$ryan = Person::read('4157236270', true);
        
        //$msg = '01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234';
        //$msg2 = '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456';

        //$smsoptions = array('nolog' => true, 'numbers' => array(array('mobileNum' => $john['mobileNum'], 'surveyId' => 28421),array('mobileNum'=>$ryan['mobileNum'], 'surveyId' => 28421)), 'message' => $msg);

        //var_dump($smsoptions);

        //$result = SmsManager::sendSMS($smsoptions);

        //echo $result;
        
    }

    public static function getuser() {
        echo Config::get('loggedIn');
    }
}

# Google Workspace Admin Setup — ProductiveRN Sheets Integration

This is a one-time setup by your Google Workspace administrator.
It allows the ProductiveRN data script to run in your organization's Google Sheets
without triggering the "unverified app" warning for any user.

---

## What You Need From ProductiveRN

Before starting, request the following from your ProductiveRN contact:

- **OAuth Client ID** (format: `123456789-abc...apps.googleusercontent.com`)

---

## Steps

### 1. Sign in to Google Admin Console
Go to [admin.google.com](https://admin.google.com) and sign in with your Workspace admin account.

### 2. Navigate to API Controls
**Security → Access and data control → API controls**

### 3. Open Third-Party App Access
Click **Manage Third-Party App Access**

### 4. Add the ProductiveRN App
1. Click **Add app → OAuth App Name Or Client ID**
2. Paste the OAuth Client ID provided by ProductiveRN
3. Click **Search**
4. Select the app from the results
5. Select which organizational units (OUs) should have access — or select **All** for the entire organization
6. Set access level to **Trusted**
7. Click **Configure**

### 5. Confirm
The app will now appear in your trusted apps list. Users in the selected OUs will no longer see the "Google hasn't verified this app" warning when running the ProductiveRN script.

---

## Scopes the App Requests

| Scope | Purpose |
|-------|---------|
| `spreadsheets` | Read and write data to the Google Sheet |
| `script.external_request` | Fetch staffing data from ProductiveRN servers |

These scopes are limited to the specific sheet the script is installed in.
The app does **not** request access to Drive, Gmail, Calendar, or any other Google services.

---

## Questions?
Contact your ProductiveRN representative.

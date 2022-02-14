## About Cabinet

Cabinet is a simple software solution for maintaining enterprise file handling and file transfer utilizing cloud file storage.  Functionality includes:

- Authentication through Polk One
- Teams with associated disk authorization
- File transaction logs
- File backups
- Ability to utilize following disk types:
    - S3 drives (other than Google S3)
    - SFTP
- Fully functional API
- Two Factor Authentication

## Build Requirements

- [PHP 8.0+](https://www.php.net/)
- [Composer 2](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- [Node Package Manager (NPM)](https://www.npmjs.com/)
- [Oracle IDCS OAuth Application](https://docs.oracle.com/en/cloud/paas/identity-cloud/uaids/add-confidential-application.html)
- [Install GnuPG PHP extension](https://serverpilot.io/docs/how-to-install-the-php-gnupg-extension/)

## Learning Cabinet

### Basic User

- File Upload
    - Click **"Upload"** on the navbar or click **"Upload a File"** if you are on the Dashboard
    - Select the disk you wish to upload to and the file you wish to upload
    - Click **"Upload"** on the form
- File Download
    - Go to the Dashboard
    - Find the disk you wish to go into and click **"Open"** on the corresponding row
    - Find the file you wish to download and click **"Download"** on the corresponding row
- Profile Management
    - Click your name at the top-right on the navbar to open a dropdown
    - Click **"Profile"** in the dropdown
    - The following info on the page:
        - Name
        - Email
        - Password
        - Two Factor Authentication
        - Browser Sessions

### Team Leader

- Change Team Name
    - While the team you want is selected, click **"Team Settings"**
    - First form changes the team name
- Adding Team Members
    - While the team you want is selected, click **"Team Settings"**
    - Enter the email of an active account in the **"Add Team Member"** form
    - Click the role you want the account to have (Administrator can delete files)
    - Click **"Add"**

### Administrator

- Driver Management
    - Fields
        - ***Driver Name***: programmatic name for the driver (view available types for options)
        - ***Driver Display Name***: name that will show for the driver
        - ***Field Name***: programmatic name for the driver field
        - ***Field Display Name***: name that will show up for the input field
        - ***Required***: whether or not the field requires an input
        - ***Encrypt Entry***: whether or not the input needs to be stored encrypted in the database
        - ***Is File***: whether or not the input needs to a file (encryption files)
    - Available types
        - s3
        - sftp
- Disk Management
    - Disk Fields
        - ***Disk Name***: display name for the disk
        - ***Driver***: driver utilized for the disk
        - ***Backup Disk***: disk that all uploads will also be uploaded to
        - ***Private***: Private disks can only be uploaded to by your team. Public disks can have files sent to them, but only your team can download from them.
        - ***Encode Files***: whether or not uploaded files will be encrypted (utilizing GnuPG)
    - Driver Fields
        - Varies based on driver used
- API Tokens
    - Click your name at the top-right on the navbar to open a dropdown
    - Click **"API Tokens"** in the dropdown
    - Add a name for the token
    - Give what permissions are needed for the token
    - Click **"Create"**
    - Copy the token when displayed on the screen, you will only be shown the code once
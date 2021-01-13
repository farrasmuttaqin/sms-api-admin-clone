# SMS API ADMIN

<img alt="GitHub top language" src="https://img.shields.io/github/languages/top/farrasmuttaqin/sms-api-admin-clone">  <img alt="GitHub repo size" src="https://img.shields.io/github/repo-size/farrasmuttaqin/sms-api-admin-clone">  <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/farrasmuttaqin/sms-api-admin-clone">  <img alt="APM badge" src="https://img.shields.io/badge/license-Apache-blue">

P.S. This is just a clone version of SMS API ADMIN 

## Getting Started
SMS API Admin is a web-based system which assists the user of SMS API services to generate and observe the report of SMS delivery status. This system authorizes users to generate and download SMS delivery reports and also manage the accounts.

## Requirements
* PHP 7.4.0++ is required but using the latest version of PHP 7.4 is highly recommended.
* Composer 1.0.0++.
* Laravel Framework 6.20.2

## Installations
```bash
composer install
```

## Showcases

![Login Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/login.png)
<p align="center">Figure 1: Login Page</p>

Login page used to sign in into the system, only Admin have credentials to login.

![Client Management Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/client_management.png)
<p align="center">Figure 2: Client Management Page</p>

Client management allows Admin to manage the Client and their related Information. Briefly, on this page there are several icon which has a branching function:
- Archived icon, allows admin to archive client.
- View icon allows admin to view information related to a client in View Client Details Page.
- Edit icon allows admin to change information related to a client in Register/Edit Client Pop Up Windows.
- Delete icon allows admin to Delete Client.
- Manage User icon allows admin to manage client’s API users in API User List.

![User Management Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/user_management.png)
<p align="center">Figure 3: User Management Page</p>

User Management allows admin to view and manage API users and their related information. Briefly, on this page there are several icon which has a branching function:
- Active icon, allows admin to activate or deactivate API user.
- View icon, allows admin to view the API user’s detail information in User Details Page.
- Pencil icon, allows admin to edit the API user detail in User Edit Page.
- Dollars icon, allows admin to manage the API user’s credit detail in User Credit Page.
- Book icon, allows admin to download API user’s billing report in Billing Report Pop Up Windows.

![User Management Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/user_management.png)
<p align="center">Figure 4: User Management Page</p>

User Management allows admin to view and manage API users and their related information. Briefly, on this page there are several icon which has a branching function:
- Active icon, allows admin to activate or deactivate API user.
- View icon, allows admin to view the API user’s detail information in User Details Page.
- Pencil icon, allows admin to edit the API user detail in User Edit Page.
- Dollars icon, allows admin to manage the API user’s credit detail in User Credit Page.
- Book icon, allows admin to download API user’s billing report in Billing Report Pop Up Windows.

![Billing Administration Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/billing_management.png)
<p align="center">Figure 5: Billing Administration Page</p>

Billing Management allows user to manage the billing information. Briefly, on this page there are several icon which has a branching function:
- Billing Profile tab allows user to view existing billing profile and create a new profile.
    There are three type of billing price:
        ◦ Tiering – the billing is based on the volume of the SMS sent by the user.
        ◦ Operator – the billing is based on the destination’s operator per SMS.
        ◦ Tiering & operator – the billing is based on the volume of the SMS sent by the user and the destination’s operator per SMS.
    - Pencil icon, allows user to edit the billing profile information in New Billing Profile Page.
    - Cross icon, allows user to delete the selected billing profile.
- Tiering Group tab allows user to create and manage existing tiering group. Tiering is one of the billing type where user is charged by a preset SMS volume. Tiering group only accept user who have registered on billing profile with Tiering based.
- Report Group tab allows user to create and manage the existing report group. Report group only accept user who have registered on billing profile with Operator based.

![Billing Administration Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/billing_management.png)
<p align="center">Figure 6: Billing Administration Page</p>

Invoice Management allows the system user to set an invoice profile based on the Billing Report for each SMS API account. Briefly, on this page there are several icon which has a branching function:
- Invoice History Tab allows user to view all invoices. It displays the invoice information of all clients. It allows user to create a new invoice, make changes and download the available records.
- Invoice Profile Tab allows user to view existing invoice profile and create a new profile. It displays the existing invoice profiles and allows user to edit the existing profiles, create invoices and views previous invoice informations.
- Invoice Settings Tab allows user to create the default content of the invoice document which the user could download as PDF later.

![Agent Administration Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/agent_management.png)
<p align="center">Figure 7: Agent Administration Page</p>

![Cobrander Administration Page](https://raw.githubusercontent.com/farrasmuttaqin/sms-api-admin-clone/Task-2-Action_to_push_sms_api_admin/screenshoot/cobrander_management.png)
<p align="center">Figure 8: Cobrander Administration Page</p>

## Author
Hi there , i'm <a href="https://github.com/farrasmuttaqin/"> Muhammad Farras Muttaqin </a>

## License
This project is open source and available under the <a href="https://github.com/farrasmuttaqin/sms-api-admin-clone/blob/main/LICENSE">Apache License 2.0</a>.

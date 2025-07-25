# 📦 Download Center App (PHP)

A **modular, session-aware PHP web application** designed for secure file distribution, optimized for same-origin API interactions and modern frontend integration.

## 🔐 Key Features

- **Secure Token-Based Session Management**  
  Implements stateless DB-backed sessions and bearer tokens for same-origin access control.

- **Modular PHP Architecture**  
  Components split into session, file streaming, and AJAX handling logic—built for maintainability and scalability.

- **Optimized File Streaming**  
  Efficient delivery of large files with progress tracking using `fread()` and chunked output techniques.

- **Frontend Integration Ready**  
  AJAX-powered UI with Bootstrap 5 components, FilePond for uploads, Tagify for smart tagging, and SweetAlert2 for responsive feedback.

- **Same-Origin Endpoint Design**  
  Built to support AJAX workflows within the same domain—no external CORS or public REST interfaces required.

- **Very Basic Codes**  
  This project doesn't require you to have very good coding/PHP skill. Just basic PHP OOP and a little BS5/JQuery frontend + API understanding, you'll understand the logic of this project very fast. Very good base if you wanted to use this as base for your simple PHP Web App. 

- **Apache 2.0 License**  
  Open-source framework supporting flexibility, attribution, and community collaboration.

## 📁 Project Structure

|Root   |                                     |
|-------|-------------------------------------|
|bin/   |-> config/ : Store APP const var     |
|       |-> entity/ : App entity-db based file|
|       |-> Store App method files.           |
|files/ |-> Store uploaded files.             |
|theme/ |-> Script/ : Store APP JS Script     |
|       |-> Style/ :  Store APP CSS Script    |
|       |-> Store App Pages.                  |
|-> app.php :  Main controller of this app.   |
|-> index.php : Entry point of this app.      |



## 🧰 Plugin Stack

| Plugin        | Purpose                                                                 |
|---------------|-------------------------------------------------------------------------|
| **Bootstrap 5** | UI components, spinners, responsive layout                            |
| **jQuery**     | DOM manipulation and AJAX requests                                     |
| **Popper.js**  | Tooltip and dropdown positioning support (used by Bootstrap)           |
| **SweetAlert2**| Stylish alerts and loading indicators                                  |
| **FilePond**   | File uploads with progress and drag-n-drop support                     |
| **Tagify**     | Smart tagging input for file metadata                                  |
| **Moment.js**  | Date/time parsing and formatting                                       |
| **DataTables** | Tabular data presentation and filtering                                |



## ⚙️ System Requirements

- **PHP 8.1+**  
- **MySQL 5.7+** (or MariaDB)  
- **Web server** (Apache, Nginx, etc.)

> No Composer or external PHP libraries required. All core logic is hand-rolled and modular.



## 🚀 Setup Instructions

1. Clone or download the repository to your web root.
2. Import the provided SQL schema to initialize the session DB.
3. Configure database credentials and other APP configuration in `bin/config/config.php`.
4. Serve via Apache/Nginx with PHP enabled.
5. Launch in browser, login and test uploads/downloads.

default system user is :  
e-mail :  admin1@localhost.com  
password : 12345  

The rest of demo user can be viewed in user management page and have password of 1234 . Please update each user password or delete them if not used.



## 🧪 Development Notes

- AJAX interactions handled via vanilla JS or jQuery.
- Token validation included in each request header or POST body.
- Ensure proper MIME types and content-length headers for optimal streaming.



## 👥 License

Licensed under the [Apache 2.0 License](https://www.apache.org/licenses/LICENSE-2.0). Fork it, refine it, deploy it.

---


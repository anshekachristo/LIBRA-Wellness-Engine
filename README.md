# Libra Health

## Overview

Libra Health is a web-based self-care assistance platform designed to provide users with accessible suggestions for common health concerns.

The application allows users to select a health issue and receive categorized suggestions covering:

* Medicine
* Home Remedies
* Body Exercises
* Relaxation Music

The system is intended to support general self-care and provide informational guidance for everyday health concerns. It is not intended to diagnose medical conditions or replace professional medical advice.

## Problem Statement

People commonly experience everyday health concerns such as headaches, stress, fever, knee pain, stomach discomfort, and other minor symptoms. Finding suitable self-care information can sometimes require searching through multiple sources.

Libra Health brings different categories of self-care suggestions together in a single web application, allowing users to select a concern and quickly access relevant information.

## Proposed Solution

Libra Health provides a simple interface where users can select a health concern and receive suggestions organized into four categories:

1. **Medicine**
   Provides information about commonly used medicines and their general usage information where applicable.

2. **Home Remedies**
   Provides household and traditional self-care suggestions that may help with selected minor concerns.

3. **Body Exercises**
   Provides simple physical activities, movements, or stretches associated with the selected concern.

4. **Relaxation Music**
   Provides music-oriented relaxation suggestions intended to support stress reduction and general well-being.

## Key Features

* User registration and login
* Health concern selection
* Health-related suggestion system
* Medicine information
* Home remedy suggestions
* Exercise and physical activity suggestions
* Relaxation music suggestions
* Search suggestions
* User profile functionality
* Profile image upload
* Personalized suggestion pages
* PHP and MySQL-based backend
* Local database integration using XAMPP

## Health Concerns

The application is designed around common everyday concerns, including examples such as:

* Headache
* Stress
* Depression-related concerns
* Knee pain
* Fever
* Stomach discomfort
* Other common health issues supported by the application

The exact concerns and available suggestions depend on the application's database and implementation.

## Technology Stack

### Frontend

* HTML
* CSS
* JavaScript

### Backend

* PHP

### Database

* MySQL

### Development Environment

* XAMPP
* Apache
* MySQL
* Visual Studio Code

## Project Structure

```text
Libra_Health_Full_V2/
│
├── assets/
├── auth/
├── db/
│   └── db.php
├── media/
├── pages/
├── uploads/
│
├── homepage.php
├── index.php
├── login.php
├── register.php
├── search_suggestions.php
├── suggest.php
├── test_profile.php
├── upload_profile.php
├── view_profile.php
├── view_suggestion.php
│
├── .gitignore
└── README.md
```

## Database

The application uses MySQL for storing application data.

The local development database is configured as:

```text
Database: health_portal
Host: localhost
```

The project is designed to run with a local XAMPP/MySQL environment.

Database credentials and environment-specific configuration should be kept local and should not be committed to the repository when they contain sensitive information.

## Installation and Setup

### 1. Install XAMPP

Install XAMPP with Apache and MySQL.

### 2. Clone the Repository

```bash
git clone https://github.com/anshekachristo/libra-health.git
```

Then move into the project directory:

```bash
cd Libra_Health_Full_V2
```

### 3. Place the Project in XAMPP

Copy the project into the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\
```

The final project location should look similar to:

```text
C:\xampp\htdocs\Libra_Health_Full_V2
```

### 4. Start XAMPP Services

Open XAMPP Control Panel and start:

* Apache
* MySQL

### 5. Configure the Database

Open phpMyAdmin and create a database named:

```text
health_portal
```

Import the required database structure/data if a database SQL file is provided separately.

### 6. Check Database Configuration

The application database connection is located in:

```text
db/db.php
```

For local XAMPP development, the configuration uses:

```text
Host: localhost
Username: root
Database: health_portal
```

Adjust the configuration if your local MySQL setup uses different credentials.

### 7. Run the Application

Open a browser and navigate to:

```text
http://localhost/Libra_Health_Full_V2/
```

## User Flow

The basic application flow is:

```text
User
  |
  v
Registration / Login
  |
  v
Homepage
  |
  v
Select Health Concern
  |
  v
View Suggestions
  |
  +------------------+
  |        |         |
  v        v         v
Medicine  Home      Exercises
          Remedies
  |
  v
Relaxation Music
```

## Security and Privacy

The project uses a `.gitignore` file to prevent local and potentially sensitive files from being uploaded to GitHub.

The repository excludes items such as:

* Environment files
* Database dumps
* Log files
* Local configuration files
* Uploaded user files
* IDE-specific files

User-uploaded images and other runtime-generated files are intended to remain outside the source-code repository.

## Medical Disclaimer

Libra Health is an educational and self-care assistance project.

The suggestions provided by the application are for general informational purposes and should not be considered a medical diagnosis or a substitute for advice from a qualified healthcare professional.

Users should consult an appropriate healthcare professional for persistent, severe, worsening, or concerning symptoms. Medication information should be verified with a qualified healthcare professional or pharmacist before use, particularly for children, pregnancy, allergies, existing medical conditions, or interactions with other medicines.

In an emergency or for severe symptoms, users should seek appropriate emergency medical assistance rather than relying on this application.

## Project Objective

The primary objective of Libra Health is to demonstrate how a web application can organize and present categorized self-care information through a simple and accessible user interface.

The project also demonstrates practical implementation of:

* PHP web development
* MySQL database integration
* User authentication
* User profiles
* Search functionality
* Dynamic content
* File uploads
* Database-driven suggestions

## Future Enhancements

Potential future improvements include:

* Responsive mobile-first interface
* Improved accessibility
* More comprehensive health information
* Professional medical-content review
* Improved medicine safety information
* Health history tracking
* Personalized recommendation mechanisms
* Multilingual support
* Improved search and filtering
* Secure cloud deployment
* Role-based administration
* Better data validation and security controls

## Author

**Ansheka Christo**

GitHub: https://github.com/anshekachristo

## License

This project is intended for educational and demonstration purposes.

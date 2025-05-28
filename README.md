# 🏥 Hospital Management System

A simple web-based Hospital Management System built using PHP and MySQL. This system allows for the management of doctors, patients, appointments, lab reports, billing, and inventory through a user-friendly interface.

---

## 🚀 Features

- **Patient Management**
  - Add, edit, delete, and list patients
- **Doctor Management**
  - Add, edit, delete, and list doctors
- **Appointment Scheduling**
  - Add and manage appointments for patients
- **Lab Reports**
  - Generate and list lab reports
  - Export reports as PDF
- **Billing System**
  - Generate patient bills
- **Inventory**
  - Track and manage hospital inventory
- **Authentication**
  - Register and login for users
  - Session-based access control

---

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, Bootstrap (if used)
- **Backend**: PHP
- **Database**: MySQL
- **PDF Generation**: FPDF library

---

## 📁 Project Structure (Overview)
hospital-management-system/
├── appointments/
│   ├── add.php
│   ├── edit.php
│   └── list.php
│
├── doctors/
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   └── list.php
│
├── patients/
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   └── list.php
│
├── fpdf182/               # FPDF library for PDF generation
│
├── billing.php            # Billing functionality
├── connection.php         # Database connection file
├── create_lab_report.php  # Create lab report
├── dashboard.php          # Main dashboard after login
├── generate_pdf.php       # Generate PDF from lab reports
├── index.php              # Landing page or entry point
├── inventory.php          # Manage inventory
├── lab_reports_list.php   # List of lab reports
├── labreport.php          # Lab report entry form
├── login.php              # Login form
├── logout.php             # Logout logic
├── register.php           # Registration page


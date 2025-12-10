# 🎓 AUI Event & Club Engagement Platform  
A modern university event management and club engagement system built with **PHP, MySQL, HTML/CSS, and JavaScript**.

The platform allows students, teachers, and staff to interact with campus events, clubs, AI-powered recommendations, reservation management, and Instagram-style stories.

---
<img width="1913" height="934" alt="image" src="https://github.com/user-attachments/assets/8357601f-3556-4923-b22a-b72b30cf79e3" />


## 🌟 Key Features

### 🧑‍🎓 Student Features
- View all upcoming events.
- Reserve and unreserve seats.
- Receive **AI-powered event recommendations** based on preferences.
- Set personal preferences (likes, dislikes, preferred time).
- View Instagram-style stories from clubs.
- Explore club profiles and contact them instantly.

---

### 👨‍🏫 Teacher Features
- All student features.
- Manage and unreserve students from events.
- View club stories and recommended events.

---

### 🧑‍💼 Staff / Club Officer Features
- Create and publish events.
- Upload event images.
- Post Instagram-style stories.
- Manage their own club identity (profile picture, content).
- Club profile pages with contact button.

---

### 🛠 Admin Features
- Full system access.
- Manage users (delete users, view details).
- Manage all events.
- Monitor reservations.
- System logs & reporting.

---

## 🧠 AI Recommendation Engine

The system includes a lightweight content-based recommendation engine:

### How it works
1. User sets **likes**, **dislikes**, **preferred event style**, and **preferred time**.  
2. Each event’s **name + description** is analyzed.  
3. Events are scored:

+40 per matching keyword in likes
-50 per matching keyword in dislikes
+10 if event matches preferred time

yaml
Copy code

4. Events are sorted by score and shown to the user.

### Result  
The student/teacher receives personalized recommendations that reflect their interests and behavior.

---

## 📸 Instagram-Style Stories

Clubs (staff accounts) can:

- Upload short story images.
- Add captions.
- Stories show in a horizontal bar like Instagram.
- Auto-expire after 24 hours.
- Clicking opens a full-screen viewer with auto-close timer.

Students and teachers can:

- View stories.
- Tap clubs to access their full profile.

---

## 🎭 Club Profiles

Each club has its own profile page showing:

- Club picture
- Club name
- Creation date
- Contact button (opens Outlook with pre-written message)
- YouTube video (optional)
- Club description (optional)

Clubs are represented as **Staff** accounts inside the `users` table.

---

## 🔐 Authentication & Roles

### User roles:
- **Student**
- **Teacher**
- **Staff (Clubs)**
- **Admin**

Features are restricted using `$_SESSION['profession']` and role logic.

Passwords are securely hashed.  
Sessions are validated for each page load.

---

## 🗄 Database Structure (Main Tables)

### `users`
Stores:
- id, name, email, password
- profession (Student / Teacher / Staff)
- role (user/admin)
- club_picture (if staff)
- created_at

### `events`
Stores:
- id, name, description
- picture
- date_time, place
- available_places
- created_by (FK to users table)

### `reservations`
Maps:
- user_id to event_id

### `stories`
Stores:
- club stories with images
- auto-expiring timestamps

### `user_preferences`
Stores:
- likes
- dislikes
- preferred_event_style
- preferred_time

---

## 🏗 Tech Stack

| Layer       | Technology               |
|-------------|---------------------------|
| Backend     | PHP 7+ (PDO)             |
| Database    | MySQL / MariaDB          |
| Frontend    | HTML5, CSS3, JavaScript  |
| Styling     | Custom UI + responsive design |
| Hosting     | Compatible with shared hosting or cloud |

---

## 🚀 Installation Guide

### 1️⃣ Clone Repository
```bash
git clone https://github.com/YOUR-USERNAME/event-platform.git
cd event-platform
2️⃣ Import Database
Import the .sql files into your MySQL server via phpMyAdmin or CLI.

3️⃣ Configure Database
Edit config.php:

php
Copy code
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_db');
define('DB_USER', 'username');
define('DB_PASS', 'password');
4️⃣ Upload Images Folder
Ensure these exist inside your root:

bash
Copy code
/uploads/events/
/uploads/stories/
/uploads/clubs/
5️⃣ Run the Project
Place files in:

htdocs/ (XAMPP/MAMP)

public_html/ (cPanel)

Visit:

arduino
Copy code
http://localhost/event-platform
or your domain.

🤝 Contributing
Contributions are welcome!

You may:

Improve UI components

Add new AI models (embeddings)

Enhance club pages

Add analytics & dashboards

Improve event discovery

📬 Contact
If you’d like to collaborate, improve this project, or report a bug:

Developer: ARH

University: Al Akhawayn University in Ifrane

📝 License
This project is available under the MIT License.

🎉 Thank You for Using the AUI Event Platform!

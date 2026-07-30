# Student Q&A Forum

This is a prototype web application designed as a simple self-contained "Student Stack Overflow", developed for the COMP1841 coursework.

It allows students to view a list of questions, post their own questions with optional screenshot attachments, and assign questions to specific course modules. 

## Features

- **User Authentication:** Secure registration and login system with password hashing.
- **Role-Based Access Control:** Differentiates between standard users and administrators.
- **Question Management:** Students can create, edit, view, and delete their own questions.
- **Module Tagging:** Questions can be categorized under specific course modules.
- **Category Filtering:** The Questions page can query and filter posts by module/category.
- **File Uploads:** Students can attach screenshots or images to their questions.
- **Admin Area:** A dedicated section for administrators to manage users and course modules.
- **Clean Aesthetic:** A traditional, easy-to-use interface built with simple CSS.

## Getting Started

### Database Setup

1. Open your XAMPP or equivalent local server.
2. Navigate to `http://localhost/phpmyadmin`.
3. Ensure you have a database named `comp1841_coursework`.
4. Import `scratch/schema.sql` to create the required tables (`accounts`, `post`, `category`, `post_category`) for a fresh setup.
5. If you are upgrading from the older version that had both `accounts` and `user`, run `scratch/migrate_merge_user_into_accounts.php` once instead. It moves `display_name` and `bio` into `accounts`, repoints posts to `accounts.id`, and removes the old `user` table.

### Accessing the Site

1. Place the project folder inside your `htdocs` (or equivalent) directory.
2. Visit the site in your browser: `http://localhost/COMP1841/CourseWork/`
3. Click on "Sign Up" to create a new student account, or login with existing credentials.

### Admin Access

An administrator account has been set up for you. To access the Admin Area:

1. Click on **Login**.
2. Enter the following credentials:
   - **Email:** `admin@example.com`
   - **Password:** `admin123`
3. Once logged in, click the **Admin Area** link in the top navigation bar.
4. From there, you can manage the student accounts (promote users to admins, or delete them) and manage the list of Module names.

## Project Structure

- `index.php`: The main welcome page.
- `posts.php`: Displays the list of all questions.
- `post_view.php`: Displays a single question in detail.
- `post_action.php`: Form logic for adding or editing a question.
- `login.php` & `signup.php`: User authentication.
- `includes/`: Contains the database connection and core functionality scripts.
- `templates/`: Contains all HTML layouts and views.
- `admin/`: Contains logic specific to the admin dashboard.
- `style.css`: The main stylesheet.

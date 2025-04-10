# WDPAI Project

## Overview

This project is a **web application** designed for managing places, users, and their interactions. It is built using **PHP**, **PostgreSQL**, and **JavaScript**, with a responsive frontend powered by **SCSS**. The application is containerized using **Docker**, ensuring a consistent development and deployment environment.

### Key Features
- **User Authentication**: Registration, login, and role-based access control.
- **Interactive Map**: Integration with **Google Maps API** to display places dynamically.
- **Place Management**: Add, edit, and view places with additional features and types.
- **Comments and Ratings**: Users can leave feedback and rate places.
- **Moderator Tools**: Manage places and comments
- **Admin Tools**: Manage users and roles.
- **Responsive Design**: Optimized for both desktop and mobile devices.

---

## Database Structure

The database is implemented in **PostgreSQL** and follows a relational model. Below is an overview of the key components:

### Tables
The database consists of the following tables:
- **`tb_user`**: Stores user information, including their company, nationality, and unique identifiers.
- **`tb_place`**: Stores information about places, such as name, address, coordinates, and associated companies.
- **`tb_comment`**: Stores user comments and ratings for places.
- **`tb_company`**: Stores information about companies.
- **`tb_nationality`**: Stores nationality information.
- **`tb_role`**: Stores roles for users.
- **`tb_type`**: Stores types of places (e.g., gas station, hotel).
- **`tb_additional`**: Stores additional features for places (e.g., parking, Wi-Fi).
- **`rel_user_role`**: Links users to roles.
- **`rel_place_type`**: Links places to their types.
- **`rel_place_additional`**: Links places to additional features.
- **`tb_login`**: Stores user login credentials.

### Views
The database includes the following views:
- **`vw_user`**: Combines user information with roles, companies, and nationalities.
- **`vw_place`**: Combines place information with types and additional features.
- **`vw_comment`**: Combines comments with user, place, and company information.

### Functions
The database includes several functions to simplify operations:
- **`fcn__avg_rating(p_place integer)`**: Calculates the average rating for a given place.
- **`fcn__getRoles(p_special_id uuid)`**: Retrieves all roles assigned to a user.
- **`fcn__getCommentByPlace(p_place integer)`**: Retrieves all comments for a specific place.
- **`fcn__loginUser(p_email character varying, p_password character varying)`**: Authenticates a user and returns their unique ID.
- **`fcn__registerUser_wrapper(p_name character varying, p_email character varying, p_password character varying)`**: A wrapper function for user registration.

### Procedures
The database includes the following procedures:
- **`prc__registerUser(p_name, p_email, p_password)`**: Registers a new user.
- **`prc__updateUserRoles(p_user_id, p_roles)`**: Updates the roles assigned to a user.

---

## Relationships and Constraints

### Relationships
- **`tb_user`**:
  - `ID_company` → `tb_company(ID_company)` (Foreign Key, `SET NULL` on delete).
  - `ID_nationality` → `tb_nationality(ID_nationality)` (Foreign Key, `SET NULL` on delete).
- **`tb_comment`**:
  - `ID_user` → `tb_user(ID_user)` (Foreign Key, `CASCADE` on delete).
  - `ID_place` → `tb_place(ID_place)` (Foreign Key, `CASCADE` on delete).
- **`rel_user_role`**:
  - `ID_user` → `tb_user(ID_user)` (Foreign Key, `CASCADE` on delete).
  - `ID_role` → `tb_role(ID_role)` (Foreign Key, `CASCADE` on delete).
- **`rel_place_type`**:
  - `ID_place` → `tb_place(ID_place)` (Foreign Key, `CASCADE` on delete).
  - `ID_type` → `tb_type(ID_type)` (Foreign Key, `CASCADE` on delete).
- **`rel_place_additional`**:
  - `ID_place` → `tb_place(ID_place)` (Foreign Key, `CASCADE` on delete).
  - `ID_additional` → `tb_additional(ID_additional)` (Foreign Key, `CASCADE` on delete).
- **`tb_login`**:
  - `ID_user` → `tb_user(ID_user)` (Foreign Key, `CASCADE` on delete).

### Constraints
- Primary keys are defined for all tables.
- Unique constraints are applied to ensure data consistency:
  - `tb_login.email` (Unique).
  - `tb_place.address_place` (Unique).
  - `tb_role.name_role` (Unique).
  - `tb_type.name_type` (Unique).
  - `tb_additional.name_additional` (Unique).

---

## Transactions and Triggers

### Transactions
- **`fcn__registerUser_wrapper`**: Executes the `prc__registerUser` procedure within a transaction to ensure atomicity and data consistency during user registration.

### Triggers
- **`tb_user`**: Monitors changes to the `tb_user` table and logs all modifications into the `tb_log` table for auditing purposes.
- **`tb_place`**: Tracks updates and deletions in the `tb_place` table, ensuring that related entries in `rel_place_type` and `rel_place_additional` are updated or removed accordingly.
- **`tb_comment`**: Automatically recalculates the average rating for a place in the `tb_place` table whenever a comment is added, updated, or deleted.

---

## Project Structure
```
WDPAI/
├── public/
│   ├── classes/          # PHP classes for database and business logic
│   ├── scripts/          # JavaScript and PHP scripts
│   │   ├── js/           # Frontend JavaScript modules
│   │   ├── php/          # Backend PHP scripts
│   ├── styles/           # SCSS and CSS files
│   ├── views/            # PHP views for different pages
│   ├── img/              # Images used in the application
├── docker/
│   ├── db/               # Database Docker setup
│   ├── nginx/            # Nginx Docker setup
│   ├── php/              # PHP Docker setup
├── docker-compose.yaml   # Docker Compose configuration
├── index.php             # Entry point for the application
├── Router.php            # Router class for handling routes
├── Application.php       # Main application class
```

---

## Setup Instructions

### Prerequisites
- Docker and Docker Compose installed on your system.

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/Waither/WDPAI
   cd WDPAI
   ```

2. Build and start the Docker containers:
   ```bash
   docker compose up --build
   ```

   This command will build and start the application along with a pre-configured database containing sample data for testing.

3. Access the application in your browser:  
   [`http://localhost:8080`](http://localhost:8080)

   #### Pre-configured users:
   - Admin:
     - Email: **`admin@mail`**
     - Password: **`admin`**
   - Moderator:
     - Email: **`moderator@mail`**
     - Password: **`moderator`**
   - User:
     - Email: **`user@mail`**
     - Password: **`user`**

4. Access pgAdmin for database management:  
   [`http://localhost:5050`](http://localhost:5050)
   - Default credentials:
     - Email: `admin@example.com`
     - Password: `admin`

---

## Screenshots

#### Places
![Places](markdown/Places.png "Places")

#### View one place
![OnePlace](markdown/OnePlace.png "One Place")

#### Map
![Map](markdown/Map.png "Map")

#### User panel
![User](markdown/User.png "User")

#### Edit user as Admin
![Admin](markdown/Admin.png "Admin")

### Authors
- Gąsior Maciej 
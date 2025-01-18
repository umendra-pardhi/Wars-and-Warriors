# Wars and Warriors

A comprehensive content management system to document and showcase the rich history of Indian wars and warriors across different eras. This project features a robust admin panel for managing historical content with multimedia support.

## Live Preview
Homepage: https://warsandwarriors.rf.gd

Admin Panel: https://warsandwarriors.rf.gd/admin/

## Features

### Admin Panel

- **Post Management**
  - Create detailed posts with thumbnail images
  - Rich text editor (Summernote.js) with support for:
    - HTML formatting
    - Image embedding
    - Video embedding
  - Draft saving functionality
  - Post status management
  - Edit and update existing posts
  - Delete posts
- **Category Management**
  - Create new categories for different eras or types of warfare
  - Edit existing category details
  - Update category information
  - Delete categories when no longer needed
- **Admin Profile Management**
  - Update admin name
  - Change email address
  - Modify password
  - Secure authentication system

## Technical Details

### Database Configuration
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wars_and_warriors";
```

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Modern web browser with JavaScript enabled

### Dependencies
- Summernote.js for rich text editing
- Bootstrap for responsive design
- jQuery for JavaScript functionality

## Installation

1. Clone the repository
```bash
git clone https://github.com/umendra-pardhi/Wars-and-Warriors.git
```

2. Import the database
- Create a new MySQL database named `wars_and_warriors`
- Import the provided SQL file from the `database` folder

3. Configure database connection
- Navigate to the `config` folder [`/config/` and `admin/config/`]
- Update database credentials in `connection.php` if different from default

4. Set up web server
- Point your web server to the project directory
- Ensure proper permissions are set for upload directories

## Usage

### Accessing Admin Panel
1. Navigate to `https://localhost/Wars-and-Warriors/admin`
2. Log in with your admin credentials
3. Default credentials:
   - Username: admin@warsandwarriors.in
   - Password: Admin@wars&warriors
   
### Managing Content
1. **Categories**
   - Use the sidebar menu to access category management
   - Fill in required fields to create new categories
   - Use edit/delete buttons for existing categories

2. **Posts**
   - Access post management from the sidebar
   - Use the rich text editor to create content
   - Upload images through the editor interface
   - Save drafts for later completion
   - Publish when ready

3. **Admin Profile**
   - Access my account from the sidebar
   - Update personal information as needed

## Support
For issues and feature requests, please create an issue in the repository or contact me.

## Contributing
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request


## Author
- Umendra Pardhi
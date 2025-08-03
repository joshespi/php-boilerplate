# PHP Boilerplate

## Environment Configuration
Create a `.env` file with the following variables:
```
DB_HOST=db
DB_ROOT_PASS=root
DB_NAME=application_db
DB_USER=admin
DB_PASS=admin
```

## Getting Started

### Build the Docker Container
```bash
docker-compose up -d --build
```

### Seed the Database
Enter the web container and run:
```bash
php config/migrate
```

### Access the Application
Open your browser and navigate to `http://localhost:8080` to use the php boilerplate.

Access phpmyadmin in your browser at `http://localhost:8081`


## Contributing
Contributions are welcome! Please submit a pull request or open an issue for enhancements
# Dockerized Laravel Project with AWS S3 Integration

This project is a Laravel application that integrates various external services using Docker. The setup includes services for Nginx, PHP-FPM, MySQL, Redis, Elasticsearch, and MailHog, providing a comprehensive environment for developing and deploying Laravel applications. Additionally, the project integrates AWS S3 for file storage.

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository:

   ```
   git clone https://github.com/josecortesdev/laravel-external-services.git
   cd laravel-external-services
   ```

2. Configure the environment variables in the `.env` file.

3. In the root of the project, build and lift the containers:
```
docker-compose up -d --build
```  

4. Install Composer dependencies:
```
docker-compose exec app composer install
``` 

## Development use

```
docker-compose up
```

## Elasticsearch

To test Elasticsearch, follow these steps:

1. Navigate to the URL http://localhost:8080/elasticsearch/form.
2. Register a document by filling in the ID, title, and content fields.
3. Click the "Index Document" button to index the document.

[ElasticSearch.webm](https://github.com/user-attachments/assets/5f628bf3-6285-41d6-817a-920ce78b61af)

## Access MailHog

To test email functionality, follow these steps:

1. Navigate to the URL http://localhost:8080/send-mail-view.
2. Click the "Send Email" button to send a test email.

To see the email captured by MailHog, navigate to the following URL in your browser:

http://localhost:8025/

[Mailhog.webm](https://github.com/user-attachments/assets/c5aa03d6-1aad-4364-b3b5-0dc12d064a18) 

## AWS S3 Integration
This application includes a simple interface for uploading, viewing, and deleting files on AWS S3.

### Configuration
Make sure you have the following AWS S3 variables set in your .env file:
`AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`

### Uploading Files
To upload a file to AWS S3, navigate to the following URL in your browser:

http://localhost:8080/s3/upload

### Viewing Files
To view the list of files stored in AWS S3, navigate to the following URL in your browser:
http://localhost:8080/s3/list

### Deleting Files
To delete a file from AWS S3, use the delete button next to the file in the list view.

[AWS3.webm](https://github.com/user-attachments/assets/7f1d8d58-5160-47c4-8bab-1d8f96c49570)



## Accessing the Database

To access the MySQL database, use the following credentials:

- **Host**: `localhost`
- **Port**: `3307`
- **Database**: `laravel`
- **Username**: `laravel`
- **Password**: `secret`

You can use a database client like MySQL Workbench, HeidiSQL, or phpMyAdmin to connect to the database.


## Suggestions
Suggestions are welcome. Please write to me at josecortesdev@gmail.com
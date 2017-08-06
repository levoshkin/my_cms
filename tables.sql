DROP TABLE IF EXISTS articles, categories, users;

CREATE TABLE articles
(
	id int NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL,
	summary text NOT NULL,
	content mediumtext NOT NULL,
	publicationDate date NOT NULL,
	category_id int NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE categories
(
	id int NOT NULL AUTO_INCREMENT,
	name varchar(255) NOT NULL,
	description text NOT NULL,
	category_parent_id int DEFAULT 0,
	category_publish int DEFAULT 0,
	ordering int DEFAULT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE users
(
	id int NOT NULL AUTO_INCREMENT,
	user_name varchar(255) NOT NULL,
	name varchar(255) NOT NULL,
	last_name varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	UNIQUE (user_name),
	PRIMARY KEY (id)
);

CREATE TABLE articles_to_categories
(
	article_id int NOT NULL,
	category_id int NOT NULL,
	article_ordering int NOT NULL
);

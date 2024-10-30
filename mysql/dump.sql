CREATE TABLE product(
   id_product INT,
   name VARCHAR(50) NOT NULL,
   description TEXT NOT NULL,
   image_url TEXT,
   stock VARCHAR(50),
   date_created DATETIME NOT NULL,
   caracteristiques_techniques TEXT,
   availability VARCHAR(50),
   price DECIMAL(15,2) NOT NULL,
   date_updated DATETIME NOT NULL,
   PRIMARY KEY(id_product),
   UNIQUE(name)
);

CREATE TABLE category(
   id_category INT,
   name VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_category),
   UNIQUE(name)
);

CREATE TABLE cart(
   id_cart INT,
   content TEXT,
   date_updated DATETIME NOT NULL,
   date_created DATETIME NOT NULL,
   PRIMARY KEY(id_cart)
);

CREATE TABLE users(
   id_user INT,
   email VARCHAR(320) NOT NULL,
   password VARCHAR(255),
   date_created DATETIME NOT NULL,
   cellphone VARCHAR(14) NOT NULL,
   role VARCHAR(50) NOT NULL,
   default_payment VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_user),
   UNIQUE(email),
   UNIQUE(cellphone)
);

CREATE TABLE country_iso3(
   id_country INT,
   iso3 VARCHAR(3) NOT NULL,
   PRIMARY KEY(id_country),
   UNIQUE(iso3)
);

CREATE TABLE password_request(
   id_password_request INT,
   token VARCHAR(50) NOT NULL,
   date_created DATETIME NOT NULL,
   date_expires DATETIME NOT NULL,
   is_expired LOGICAL NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_password_request),
   UNIQUE(token),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE pile_mails(
   id_mail INT,
   date_created DATETIME NOT NULL,
   status VARCHAR(50) NOT NULL,
   recipient VARCHAR(320) NOT NULL,
   content TEXT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_mail),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE invoice(
   id_invoice INT,
   file_url TEXT NOT NULL,
   date_created DATETIME NOT NULL,
   PRIMARY KEY(id_invoice),
   UNIQUE(file_url)
);

CREATE TABLE credit_card(
   id_credit_card INT,
   numbers VARCHAR(50) NOT NULL,
   crypto VARCHAR(3) NOT NULL,
   expiration DATETIME NOT NULL,
   name VARCHAR(255),
   id_user INT NOT NULL,
   PRIMARY KEY(id_credit_card),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE orders(
   id_order INT,
   date_validated DATETIME NOT NULL,
   order_uuid VARCHAR(50) NOT NULL,
   total_price DECIMAL(15,2) NOT NULL,
   status VARCHAR(50) NOT NULL,
   delivery_address TEXT NOT NULL,
   eta DATETIME NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_order),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE users_addresses(
   id_address INT,
   address VARCHAR(320),
   city VARCHAR(50) NOT NULL,
   zip INT NOT NULL,
   id_country INT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_address),
   FOREIGN KEY(id_country) REFERENCES country_iso3(id_country),
   FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE contenir(
   id_product INT,
   id_category INT,
   PRIMARY KEY(id_product, id_category),
   FOREIGN KEY(id_product) REFERENCES product(id_product),
   FOREIGN KEY(id_category) REFERENCES category(id_category)
);

CREATE TABLE remplir(
   id_product INT,
   id_cart INT,
   PRIMARY KEY(id_product, id_cart),
   FOREIGN KEY(id_product) REFERENCES product(id_product),
   FOREIGN KEY(id_cart) REFERENCES cart(id_cart)
);

CREATE TABLE regrouper(
   id_product INT,
   id_order INT,
   PRIMARY KEY(id_product, id_order),
   FOREIGN KEY(id_product) REFERENCES product(id_product),
   FOREIGN KEY(id_order) REFERENCES orders(id_order)
);

CREATE TABLE recevoir(
   id_user INT,
   id_invoice INT,
   PRIMARY KEY(id_user, id_invoice),
   FOREIGN KEY(id_user) REFERENCES users(id_user),
   FOREIGN KEY(id_invoice) REFERENCES invoice(id_invoice)
);

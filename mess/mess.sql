CREATE DATABASE mess;

USE mess;

CREATE TABLE employee
(
	emp_id VARCHAR(20) NOT NULL,
	name VARCHAR(45) NOT NULL,
	address VARCHAR(100),
	PRIMARY KEY (emp_id)
);

CREATE TABLE operator
(
	emp_id VARCHAR(20) NOT NULL,
	uname VARCHAR(45) NOT NULL,
	pwd VARCHAR(45) NOT NULL,
	PRIMARY KEY (emp_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE manager
(
	emp_id VARCHAR(20) NOT NULL,
	uname VARCHAR(45) NOT NULL,
	pwd VARCHAR(45) NOT NULL,
	PRIMARY KEY (emp_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);
	
CREATE TABLE diner
(
	emp_id VARCHAR(20) NOT NULL,
	uname VARCHAR(45) NOT NULL,
	pwd VARCHAR(45) NOT NULL,
	PRIMARY KEY (emp_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE stock
(
	item_id INT NOT NULL,
	name VARCHAR(20),
	qty FLOAT NOT NULL,
	PRIMARY KEY (item_id)
);

CREATE TABLE red_leave
(
	emp_id VARCHAR(20) NOT NULL,
	_from DATE NOT NULL,
	_to DATE,
	days INT,
	PRIMARY KEY (emp_id, _from),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE extras
(
	purchase_id INT NOT NULL AUTO_INCREMENT,
	emp_id VARCHAR(20) NOT NULL,	
	item_id INT NOT NULL,
	item_name VARCHAR(20),
	_date DATE NOT NULL,
	price FLOAT NOT NULL,
	PRIMARY KEY (purchase_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (item_id) REFERENCES stock (item_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE coupon
(
	c_id INT NOT NULL AUTO_INCREMENT,
	emp_id VARCHAR(20) NOT NULL,
	_date DATE NOT NULL,
	type VARCHAR(20),
	cost FLOAT NOT NULL,
	PRIMARY KEY (c_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE payment
(
	bill_id INT NOT NULL AUTO_INCREMENT,
	emp_id VARCHAR(20) NOT NULL,
	method VARCHAR(20) NOT NULL,
	_date DATE NOT NULL,
	amount FLOAT NOT NULL,
	PRIMARY KEY (bill_id),
	FOREIGN KEY (emp_id) REFERENCES employee (emp_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE order_to_kitchen
(
	_date DATE NOT NULL,
	PRIMARY KEY (_date)
);

CREATE TABLE order_date
(
	_date DATE NOT NULL,
	item_id INT NOT NULL,
	item_name VARCHAR(20),
	qty FLOAT NOT NULL,
	PRIMARY KEY (_date, item_id),
	FOREIGN KEY (_date) REFERENCES order_to_kitchen (_date) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (item_id) REFERENCES stock (item_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE cashbook
(
	month VARCHAR(20) NOT NULL,
	year INT NOT NULL,
	income FLOAT NOT NULL,
	expenditure FLOAT NOT NULL,
	profit FLOAT NOT NULL,
	PRIMARY KEY (month, year)
);

CREATE TABLE purchase
(
	txn_id INT NOT NULL,
	_date DATE NOT NULL,
	PRIMARY KEY (txn_id)
);

CREATE TABLE purchase_date
(
	txn_id INT NOT NULL,
	item_id VARCHAR(20) NOT NULL,
	qty FLOAT NOT NULL,
	cost FLOAT NOT NULL,
	PRIMARY KEY (item_id, txn_id),
	FOREIGN KEY (txn_id) REFERENCES purchase (txn_id) ON DELETE CASCADE ON UPDATE CASCADE
);







INSERT INTO employee VALUES (1, "Adam", "no 34, tux st, cont");
INSERT INTO employee VALUES (2, "Bill", "no 34, tux st, cont");
INSERT INTO employee VALUES (3, "Ragu", "no 34, tux st, cont");
INSERT INTO employee VALUES (4, "Sai", "no 34, tux st, cont");
INSERT INTO employee VALUES (5, "Vig", "no 34, tux st, cont");
INSERT INTO employee VALUES (6, "Rahul", "no 34, tux st, cont");



INSERT INTO operator VALUES (6, "rahul", "pwd");


INSERT INTO manager VALUES (5, "viggnah", "pwd");


INSERT INTO diner VALUES (1, "Adam", "pass");
INSERT INTO diner VALUES (2, "Bill", "pass");
INSERT INTO diner VALUES (3, "Ragu", "pass");
INSERT INTO diner VALUES (4, "Sai", "pass");



INSERT INTO cashbook VALUES ("Oct", 2017, 20000, 15000, 5000);
INSERT INTO cashbook VALUES ("Nov", 2017, 30000, 21000, 9000);
INSERT INTO cashbook VALUES ("Dec", 2017, 19000, 25000, -6000);
INSERT INTO cashbook VALUES ("Jan", 2018, 32000, 24000, 8000);
INSERT INTO cashbook VALUES ("Feb", 2018, 20000, 23000, -3000);




INSERT INTO stock VALUES (1, "Lime Juice", 10);
INSERT INTO stock VALUES (2, "Pepsi", 15);
INSERT INTO stock VALUES (3, "Chocolate", 75);
INSERT INTO stock VALUES (4, "Icecream", 10);
INSERT INTO stock VALUES (5, "Biscuit", 75);
INSERT INTO stock VALUES (6, "Curd", 10);




INSERT INTO extras VALUES (1, 2, 1, "Lime Juice", "2017-10-20", 9);
INSERT INTO extras VALUES (2, 1, 1, "Lime Juice", "2017-10-21", 9);
INSERT INTO extras VALUES (3, 3, 4, "Icecream", "2017-10-29", 15);
INSERT INTO extras VALUES (4, 3, 5, "Biscuit", "2017-10-31", 20);
INSERT INTO extras VALUES (5, 1, 6, "Curd", "2017-11-03", 12);
INSERT INTO extras VALUES (6, 3, 1, "Lime Juice", "2017-11-10", 9);
INSERT INTO extras VALUES (7, 3, 2, "Pepsi", "2017-11-15", 20);
INSERT INTO extras VALUES (8, 3, 5, "Biscuit", "2017-11-20", 9);
INSERT INTO extras VALUES (9, 1, 3, "Chocolate", "2018-01-10", 20);
INSERT INTO extras VALUES (10, 4, 3, "Chocolate", "2018-02-11", 20);




INSERT INTO coupon VALUES (1, 1, "2017-10-20", "breakfast", 25);
INSERT INTO coupon VALUES (2, 2, "2017-10-29", "lunch", 35);
INSERT INTO coupon VALUES (3, 4, "2017-11-20", "breakfast", 25);
INSERT INTO coupon VALUES (4, 1, "2017-12-10", "breakfast", 25);
INSERT INTO coupon VALUES (5, 1, "2017-12-20", "dinner", 30);
INSERT INTO coupon VALUES (6, 3, "2018-01-20", "dinner", 30);



INSERT INTO order_to_kitchen VALUES ("2017-10-24");
INSERT INTO order_to_kitchen VALUES ("2017-10-25");
INSERT INTO order_to_kitchen VALUES ("2017-10-26");
INSERT INTO order_to_kitchen VALUES ("2017-10-27");



INSERT INTO order_date VALUES ("2017-10-24", 1, "Lime Juice", 30);
INSERT INTO order_date VALUES ("2017-10-24", 2, "Pepsi", 30);
INSERT INTO order_date VALUES ("2017-10-24", 5, "Biscuit", 30);
INSERT INTO order_date VALUES ("2017-10-25", 6, "Curd", 30);
INSERT INTO order_date VALUES ("2017-10-26", 2, "Pepsi", 30);
INSERT INTO order_date VALUES ("2017-10-26", 3, "Chocolate", 30);
INSERT INTO order_date VALUES ("2017-10-27", 4, "Icecream", 30);
INSERT INTO order_date VALUES ("2017-10-27", 6, "Curd", 30);



INSERT INTO purchase VALUES (1, "2017-10-24");
INSERT INTO purchase VALUES (2, "2017-10-25");
INSERT INTO purchase VALUES (3, "2017-10-26");
INSERT INTO purchase VALUES (4, "2017-10-27");


INSERT INTO purchase_date VALUES (1, 1, 30, 300);
INSERT INTO purchase_date VALUES (1, 2, 20, 350);
INSERT INTO purchase_date VALUES (1, 4, 55, 425);
INSERT INTO purchase_date VALUES (2, 6, 50, 300);
INSERT INTO purchase_date VALUES (2, 5, 20, 300);
INSERT INTO purchase_date VALUES (3, 5, 100, 800);
INSERT INTO purchase_date VALUES (3, 4, 80, 550);
INSERT INTO purchase_date VALUES (4, 3, 90, 475);
INSERT INTO purchase_date VALUES (4, 1, 70, 390);
INSERT INTO purchase_date VALUES (4, 2, 20, 300);
INSERT INTO purchase_date VALUES (4, 6, 35, 350);








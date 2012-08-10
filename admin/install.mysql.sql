
CREATE TABLE IF NOT EXISTS #__proposal_deliverable (
	deliverable_id int(6) auto_increment primary key,
	user_id int(6), 
	title varchar(255), 
	description varchar(255),
	type tinyint(4), 
	cost float(10,2),
	deadline_date date)ENGINE=InnoDB DEFAULT CHARSET=utf8;






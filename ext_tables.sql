#
# Extend table 'pages'
# used for news pagetype
#
CREATE TABLE pages (
	tx_newspage_date       datetime default null,
	tx_newspage_categories varchar(50) default '' not null
);

CREATE TABLE tx_newspage_domain_model_category (
	name varchar(50) default '' not null
);

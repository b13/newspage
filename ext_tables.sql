#
# Extend table 'pages'
# used for event pagetype
#
CREATE TABLE pages (
	tx_newspage_date     date default null,
	tx_newspage_category int(11) DEFAULT 0 NOT NULL
);

CREATE TABLE tx_newspage_domain_model_category (
	uid              int(11) unsigned           NOT NULL auto_increment,
	pid              int(11) unsigned DEFAULT 0 NOT NULL,

	sorting          int(11) default 0          not null,
	sys_language_uid int(11) DEFAULT 0          NOT NULL,
	l10n_parent      int(11) DEFAULT 0          NOT NULL,
	l10n_diffsource  mediumblob,
	l10n_source      int(11) DEFAULT 0          NOT NULL,

	name             varchar(50) DEFAULT ''     NOT NULL,

	PRIMARY KEY (uid)
);

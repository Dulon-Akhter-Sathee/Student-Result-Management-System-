SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE admin (
  id int(11) NOT NULL,
  UserName varchar(100) DEFAULT NULL,
  Password varchar(100) DEFAULT NULL,
  updationDate timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE admin
ADD PRIMARY KEY (id);

ALTER TABLE admin
MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

INSERT INTO admin (id, UserName, Password, updationDate) VALUES
(1, 'admin', 'f925916e2754e5e03f75dd58a5733251', '2025-10-28 10:30:57');

CREATE TABLE tblclasses (
  id int(11) NOT NULL,
  ClassName varchar(80) DEFAULT NULL,
  ClassNameNumeric int(4) DEFAULT NULL,
  Section varchar(5) DEFAULT NULL,
  CreationDate timestamp NULL DEFAULT current_timestamp()
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

  ALTER TABLE tblclasses
  ADD PRIMARY KEY (id);
  
  ALTER TABLE tblclasses
  MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT = 2;


  CREATE TABLE tblsubjects (
  id int(11) NOT NULL,
  SubjectName varchar(100) NOT NULL,
  SubjectCode varchar(100) DEFAULT NULL,
  CreationDate timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE tblsubjects
ADD PRIMARY KEY (id);

ALTER TABLE tblsubjects
MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT =2;

CREATE TABLE tblsubjectcombination (
  id int(11) NOT NULL,
  ClassId int(11) DEFAULT NULL,
  SubjectId int(11) DEFAULT NULL,
  status int(1) DEFAULT NULL,
  CreationDate timestamp NULL DEFAULT current_timestamp(),
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE tblsubjectcombination
ADD PRIMARY KEY (id);

ALTER TABLE tblsubjectcombination
  MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT =2;

CREATE TABLE tblstudents (
  StudentId int(11) NOT NULL,
  StudentName varchar(100) DEFAULT NULL,
  RollId varchar(100) DEFAULT NULL,
  StudentEmail varchar(100) DEFAULT NULL,
  Gender varchar(10) DEFAULT NULL,
  DOB varchar(100) DEFAULT NULL,
  ClassId int(11) DEFAULT NULL,
  RegDate timestamp NULL DEFAULT current_timestamp(),
  Status int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE tblstudents
ADD PRIMARY KEY (StudentId);

ALTER TABLE tblstudents
 MODIFY StudentId int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT =2;

CREATE TABLE tblresult (
  id int(11) NOT NULL,
  StudentId int(11) DEFAULT NULL,
  ClassId int(11) DEFAULT NULL,
  SubjectId int(11) DEFAULT NULL,
  marks int(11) DEFAULT NULL,
  PostingDate timestamp NULL DEFAULT current_timestamp(),
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE tblresult
  ADD PRIMARY KEY (id);

ALTER TABLE tblresult
  MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT =2;



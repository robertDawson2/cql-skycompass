-- phpMyAdmin SQL Dump
-- version 2.11.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2009 at 07:35 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'quickbooks_import'
--

-- --------------------------------------------------------

--
-- Table structure for table 'qb_example_customer'
--

CREATE TABLE IF NOT EXISTS customers (
  list_id varchar(40) NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  name varchar(50) NOT NULL,
  full_name varchar(255) NOT NULL,
  first_name varchar(40) NOT NULL,
  middle_name varchar(10) NOT NULL,
  last_name varchar(40) NOT NULL,
  contact varchar(50) NOT NULL,
  PRIMARY KEY  (list_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- --------------------------------------------------------

/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50051
Source Host           : 127.0.0.1:3306
Source Database       : isdrequestmt_db

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2013-07-10 23:15:39
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `tbl_comclaim`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_comclaim`;
CREATE TABLE `tbl_comclaim` (
  `FComClaimID` int(11) NOT NULL default '0',
  `FComClaim` varchar(250) default NULL,
  PRIMARY KEY  (`FComClaimID`)
) ENGINE=MyISAM DEFAULT CHARSET=tis620;

-- ----------------------------
-- Records of tbl_comclaim
-- ----------------------------
INSERT INTO tbl_comclaim VALUES ('8', 'ร้านอนันต์ชัยอลูมิเนียม T 02-7356919/02-7351828');
INSERT INTO tbl_comclaim VALUES ('65', 'บ.คัลมิเนท แอรี่ จ. 02-7486758-9 , 02-7458244-6');
INSERT INTO tbl_comclaim VALUES ('10', 'บ.อเมริกาน่า คอมพิวเตอร์ ซิสเต็ม  T 02-866-1380');
INSERT INTO tbl_comclaim VALUES ('91', 'บจก.ซัยโจ เด็นกิ อินเตอร์เนชั่นแนล  Tel.02-8321999');
INSERT INTO tbl_comclaim VALUES ('12', 'บ.ไพลอนเทค T.028053004');
INSERT INTO tbl_comclaim VALUES ('13', 'ร้านเพทายเอ็นเตอร์ไพรส์ T.02-5276111,02-5274043');
INSERT INTO tbl_comclaim VALUES ('85', 'ห้างหุ้นส่วนจำกัด โฮซันนา  คุณอรัญ  Tel. 089-681-4584');
INSERT INTO tbl_comclaim VALUES ('6', 'บ. ทรีค เคมิคอล T 02-432-6232-8');
INSERT INTO tbl_comclaim VALUES ('5', 'ร้าน ป. ผาสุก T 025713661');
INSERT INTO tbl_comclaim VALUES ('3', 'เฉลิมชัย  T. 02952-3657/ 02-952-3660');
INSERT INTO tbl_comclaim VALUES ('4', 'แคลายเครื่องเย็น  T .02-580-1705 / 02-580-5453');
INSERT INTO tbl_comclaim VALUES ('1', 'บ. ไชยสงวน 02-562280, 025264696');
INSERT INTO tbl_comclaim VALUES ('2', 'บ. บุญประเสริฐ  02-9207905-6');
INSERT INTO tbl_comclaim VALUES ('14', 'หจก.ช.ถาวรซัพพลาย t. 02-5896190,02-9523998');
INSERT INTO tbl_comclaim VALUES ('15', 'ร้านเทคอิเล็กทรอนิกส์ T. 025262878');
INSERT INTO tbl_comclaim VALUES ('16', 'บจ.ยิ่งใหญ่อิเล็กทรอนิค T. 02-968-6460-1');
INSERT INTO tbl_comclaim VALUES ('17', 'ห้าง ลัคกี้มิตซู T.02-6111071-2');
INSERT INTO tbl_comclaim VALUES ('18', 'หจก. แสงประดิษฐ์ T.02-9510910');
INSERT INTO tbl_comclaim VALUES ('19', 'ปากเกร็ดแอร์ T .  02-9644642');
INSERT INTO tbl_comclaim VALUES ('20', 'หจก.เอส.เอ.แอล ซัพพลาย T.02-9031664');
INSERT INTO tbl_comclaim VALUES ('21', 'บ. เทเลพาร์ท คอร์ปอเรชั่น T.023975833-4');
INSERT INTO tbl_comclaim VALUES ('22', 'บ. ติวานนท์แอร์เซ็นเตอร์ จำกัด T.02-5882932');
INSERT INTO tbl_comclaim VALUES ('23', 'บ.นำแสง เอ็นจิเนียริ่ง จำกัด T 02-2948946-55');
INSERT INTO tbl_comclaim VALUES ('24', 'บ. ที.เค.เค.เอ็นจิเนียริ่ง จำกัด T.02-2948946-55');
INSERT INTO tbl_comclaim VALUES ('25', 'บ. คาลปีด้า (ประเทสไทย จำกัด T.02-2750027');
INSERT INTO tbl_comclaim VALUES ('26', 'กอยิ่งเจริญ T 02-5265360');
INSERT INTO tbl_comclaim VALUES ('27', 'อเมริกัน  สแตนดาร์ต T 02-1022222 ต่อ 2');
INSERT INTO tbl_comclaim VALUES ('28', 'บ.สหรุ่งโรจน์ (ประเทศไทย) T.02-6227311-5');
INSERT INTO tbl_comclaim VALUES ('29', 'บ.เอส.เค.อีเล็คตริค ไลท์ติ้ง จำกัด T 02-5033844-5');
INSERT INTO tbl_comclaim VALUES ('30', 'นุกูล พี.วี.ซี (แคลาย) T. 02-952-4050');
INSERT INTO tbl_comclaim VALUES ('31', 'บ.ยูนิตี้ เซ็นเตอร์ T.02-503-6277');
INSERT INTO tbl_comclaim VALUES ('32', 'บ.ขุนพล  สูบน้ำเสียทุกชนิด T. 02-5278348 , 081-9393185');
INSERT INTO tbl_comclaim VALUES ('33', 'บ.อมรศูนย์รวมอะไหล่อีเล็คทรอนิคส์(สาขาเมเจอร์นนท์) 02-525-4195-8');
INSERT INTO tbl_comclaim VALUES ('34', 'บ. OK.อีเล็คทรอนิค จำหน่ายอุปกรณ์ไฟฟ้า โคมไฟราคาถูก T. 02-9249071, 02-9249041');
INSERT INTO tbl_comclaim VALUES ('35', 'บ.แสงไพโรชน์ ไล้ท์ติ้ง เซ็นเตอร์ จ.  T. 02-951-9966');
INSERT INTO tbl_comclaim VALUES ('36', 'บ.บุรีเจริญมอเตอร์ ซ่อมมอเตอร์สว่านไฟฟ้า T. 086-022-9348');
INSERT INTO tbl_comclaim VALUES ('37', 'บ.นำสินกระจกและอลูมิเนียม จ. T.081-8146953');
INSERT INTO tbl_comclaim VALUES ('38', 'อนันตชัย อลูมิเนียม T. 086-3008839 k.อดิศร');
INSERT INTO tbl_comclaim VALUES ('39', 'สถิตย์  กุลกนก  (ช่างน้อย) T. 02-815-9764');
INSERT INTO tbl_comclaim VALUES ('40', 'ทรัพท์รุ่งเรืองค้าของเก่า k. หน่อย T. 02-9232468');
INSERT INTO tbl_comclaim VALUES ('43', 'บ.ยิ่งเจริญ อิเล็คทริค จ. T. 02-696-7487');
INSERT INTO tbl_comclaim VALUES ('44', 'ช่างม๊อด T. 087-0294205');
INSERT INTO tbl_comclaim VALUES ('45', 'ม่านเรวดี (อรทัย)   T. 02-526-4794 , 02-9695766');
INSERT INTO tbl_comclaim VALUES ('46', 'บ.อิเล็คทริค สแควร์ จ. (พิสิฐ)  02-9502075-6');
INSERT INTO tbl_comclaim VALUES ('47', 'K.ราชัน T. 081-942-5079  FAX. 02-9695859');
INSERT INTO tbl_comclaim VALUES ('48', 'บุญประเสริฐ  T. 02-9207905  FAX. 02-920-7907');
INSERT INTO tbl_comclaim VALUES ('49', 'อะไหล่ Carricr  ทุกชนิด  02-9101673-1693 , 02-7361818 k. หน่อย');
INSERT INTO tbl_comclaim VALUES ('66', 'บ.เฟดเดิรัล แมททีเรียล จ. 02-5675885-7 , 081-4410953');
INSERT INTO tbl_comclaim VALUES ('51', 'hw ');
INSERT INTO tbl_comclaim VALUES ('52', 'บ.ที.เอ็ม.ไฟร์ เคมิคอล จ. T.02-9299295');
INSERT INTO tbl_comclaim VALUES ('53', 'บ.ฟิลเตอร์ วิชั่น จ. T.02-5182722,086-6006790');
INSERT INTO tbl_comclaim VALUES ('54', 'หจก.เจเจ คูลลิ่ง เซลส์ แอนด์ เซอร์วิส T.084-1050972,02-551-2437');
INSERT INTO tbl_comclaim VALUES ('55', 'บจก.สหสินไทย ค้าวัตดุก่อสร้าง T.02-9513766,089-0750400');
INSERT INTO tbl_comclaim VALUES ('56', 'นวดี,ชุมพร T.081-9393185');
INSERT INTO tbl_comclaim VALUES ('57', 'กาญจนเดช 02-8798617');
INSERT INTO tbl_comclaim VALUES ('58', 'ร้านเมล่อนแอร์ 08-1911-6406');
INSERT INTO tbl_comclaim VALUES ('59', 'ร้าน MK  02-735-0011');
INSERT INTO tbl_comclaim VALUES ('99', 'บจก.พรนิพัฒน์ บริการหม้อแปลงไฟฟ้า  K.ทิวา เปลี่ยนชื่นTel. 087-0372837 , 081-4074312 , 02-8328355');
INSERT INTO tbl_comclaim VALUES ('61', 'นนอะไหล่ยนต์  02-5914887,02-9524235,02-9523396');
INSERT INTO tbl_comclaim VALUES ('62', 'บ.ทวินอีเล็คทริค  02-5833292-3');
INSERT INTO tbl_comclaim VALUES ('63', 'โฮมอิเล็คทริค  02-5833292-3');
INSERT INTO tbl_comclaim VALUES ('64', 'จำหน่ายโปรแกรม Zeal 081-8044177');
INSERT INTO tbl_comclaim VALUES ('67', 'หจก.แสงประดิษฐ์ 02-9510910 ,02-9510760');
INSERT INTO tbl_comclaim VALUES ('68', 'บ.สหเอเซียอิเล็คทริคจ.  02-9217766-7');
INSERT INTO tbl_comclaim VALUES ('69', 'บ.บุญประเสริฐ  02-9207905-6 , 081863-0022');
INSERT INTO tbl_comclaim VALUES ('70', 'บ.วอเตอร์ดอกเตอร์ 02-5592920 , 0816127706');
INSERT INTO tbl_comclaim VALUES ('71', 'บ.ไทเกอร์สตาร์คอมมูมิเคชั่น  02-682-4445-8');
INSERT INTO tbl_comclaim VALUES ('72', 'บ.โชคประเสริฐ จ. 02-8321999 , 02-8321991');
INSERT INTO tbl_comclaim VALUES ('73', 'อเมริกัน สแตนดาร์ต  02-1022222 ต่อ 2  , 02-1022030');
INSERT INTO tbl_comclaim VALUES ('86', 'เอ็นบี วาย ดิสทริบิวเทอร์ส (คุณศิลป์ชัย  สุขขา) Tel.08-9910-9662');
INSERT INTO tbl_comclaim VALUES ('75', 'รัตติกร ไตรญาณสม  089-793-6026');
INSERT INTO tbl_comclaim VALUES ('76', 'homework');
INSERT INTO tbl_comclaim VALUES ('77', 'คาร์ฟูร์');
INSERT INTO tbl_comclaim VALUES ('78', 'บจก.ไทย พีเอซี อินดัสตรี T 02-8949831-3');
INSERT INTO tbl_comclaim VALUES ('79', 'แกรนด์ โฮม มาร์ท 02-5894469');
INSERT INTO tbl_comclaim VALUES ('80', 'เด่นศักดิ์  TEL.');
INSERT INTO tbl_comclaim VALUES ('81', 'บิ๊กซี    Tel.02-950-4888   Fax.02-950-4822');
INSERT INTO tbl_comclaim VALUES ('82', 'บุญถาวร  เกษตร-นวมินทร์  Tel.02-7912777');
INSERT INTO tbl_comclaim VALUES ('83', 'บุญถาวร  ปิ่นเกล้า Tel.02-4413075');
INSERT INTO tbl_comclaim VALUES ('84', 'บุญถาวร  รังสิต   Tel.02-9020171');
INSERT INTO tbl_comclaim VALUES ('87', 'หจก.เกล้าชัยวัสดุภัณฑ์  Tel. 02-9693135-40');
INSERT INTO tbl_comclaim VALUES ('88', 'บจก.เฟรช โฟลว์ เซอร์วิส  คุณปคุณ  Tel. 081-835-2685');
INSERT INTO tbl_comclaim VALUES ('89', 'หจก.เที่ยงมีชัย  Tel. 02-929-8840');
INSERT INTO tbl_comclaim VALUES ('90', 'บจก.ฐิติกุล แอสโซซิเอทส์  K.มารุต  Tel.084-0987248');
INSERT INTO tbl_comclaim VALUES ('92', 'บจก. เอส. เค. อิเลคตริค ไลท์ติ้ง จำกัด Tel. 02-5033844-5 /  Fax.02-5033843');
INSERT INTO tbl_comclaim VALUES ('93', 'บจก. บีออนเนสท์  Tel. 02-5402752  /  Fax. 02-9188322');
INSERT INTO tbl_comclaim VALUES ('94', 'ช.ชัยชนะ บ้านหม้อ โทร.02-2226685 / รังสิต โทร.02-5330974-5');
INSERT INTO tbl_comclaim VALUES ('95', 'บจก.พี. พี. คอมพิวเตอร์ ซีสเต็ม จำกัด T.02-2215008  F.02-2215190 จำหน่ายสายเคเบิ้ล, CCTV,ระบบโทรศัพท์ ');
INSERT INTO tbl_comclaim VALUES ('96', 'บจก.แอนนาดิจิท กรุ๊ป  T.02-930-9071-6  F.02-930-9001  Mobile.  081-2989840 คุณวันวิสา ');
INSERT INTO tbl_comclaim VALUES ('97', 'บจก.กรีนพลาน่า  T.02-9248252-4  F.02-9248255');
INSERT INTO tbl_comclaim VALUES ('98', 'หจก.ส่งเสริมกสิกรไทย  Tel. 02-571-7234');

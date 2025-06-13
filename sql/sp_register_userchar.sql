DELIMITER //

DROP PROCEDURE IF EXISTS sp_register_userchar //

CREATE PROCEDURE sp_register_userchar (
  IN in_username VARCHAR(16),
  IN in_email    VARCHAR(48),
  IN in_password VARCHAR(32)
)
BEGIN
  DECLARE v_char_id INT;
  DECLARE v_error_msg TEXT;
  DECLARE v_user_exists  INT DEFAULT 0;
  DECLARE v_email_exists INT DEFAULT 0;
  
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
    
    ROLLBACK;
    
    SELECT 'error' AS result, v_error_msg AS message;
  END;
  
  -- Check for existing username.
  SELECT COUNT(*)
  INTO   v_user_exists
  FROM   game_users
  WHERE  username = in_username;
  
  -- Check for existing email.
  SELECT COUNT(*)
  INTO   v_email_exists
  FROM   game_users
  WHERE  email = in_email;
  
  IF v_user_exists > 0 THEN
    SELECT 'fail' AS result, 'Username is taken' AS message;
    
  ELSEIF v_email_exists > 0 THEN
    SELECT 'fail' AS result, 'Email is taken' AS message;
    
  ELSE
    START TRANSACTION;
      
      -- INSERT game_user
      INSERT INTO game_users (username, email, pass_word)
      VALUES (in_username, in_email, in_password);
      
      -- Get last inserted id
      SET v_char_id = LAST_INSERT_ID();
      
      -- INSERT into other tables
      INSERT INTO char_attributes  (char_id) VALUES (v_char_id);
      INSERT INTO style_attributes (char_id) VALUES (v_char_id);
      INSERT INTO skill_training   (char_id) VALUES (v_char_id);
      INSERT INTO stats            (char_id) VALUES (v_char_id);
      INSERT INTO char_team        (char_id) VALUES (v_char_id);
      
    COMMIT;
  
    SELECT 'success' AS result, v_char_id AS char_id;
  END IF;
END //

DELIMITER ;

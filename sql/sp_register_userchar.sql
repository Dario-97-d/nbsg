DELIMITER //

DROP PROCEDURE IF EXISTS sp_register_userchar //
CREATE PROCEDURE sp_register_userchar (
    IN p_username  VARCHAR(16),
    IN p_email     VARCHAR(48),
    IN p_pass_word VARCHAR(32)
)
BEGIN
    DECLARE v_char_id INT;
	DECLARE v_error_msg TEXT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
		
        ROLLBACK;
        
		SELECT 'error' AS result, v_error_msg AS message;
    END;

    START TRANSACTION;

    -- INSERT game_user
    INSERT INTO game_users (username, email, pass_word)
    VALUES (p_username, p_email, p_pass_word);

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
END //

DELIMITER ;

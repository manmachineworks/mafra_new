INSERT INTO `sms_templates` (`id`, `identifier`, `sms_body`, `template_id`, `status`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'login_with_otp', 'Your otp verification code is [[code]]', NULL, '1', current_timestamp(), current_timestamp());

ALTER TABLE `users` 
ADD `otp_code` INT NULL AFTER `verification_code`, 
ADD `otp_sent_time` TIMESTAMP NULL AFTER `otp_code`;

COMMIT;
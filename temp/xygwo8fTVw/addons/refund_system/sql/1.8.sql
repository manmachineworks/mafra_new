ALTER TABLE `products` ADD `refund_note_id` BIGINT(20) NULL AFTER `refundable`;
ALTER TABLE `refund_requests` ADD `images` TEXT NULL AFTER `reason`;

COMMIT;

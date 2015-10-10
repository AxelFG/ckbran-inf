INSERT INTO `#__helpdeskpro_configs` (`id`, `config_key`, `config_value`) VALUES
(1, 'allow_public_user_submit_ticket', '0'),
(2, 'date_format', 'l, d M Y g:i a'),
(3, 'default_ticket_priority_id', '3'),
(4, 'new_ticket_status_id', '1'),
(5, 'ticket_status_when_customer_add_comment', '2'),
(6, 'ticket_status_when_admin_add_comment', '3'),
(7, 'closed_ticket_status', '4'),
(8, 'allowed_file_types', 'jpg|jepg|gzip|doc|pdf|docx|exe|zip'),
(9, 'max_number_attachments', '3'),
(10, 'send_ticket_attachments_to_email', '1'),
(11, 'from_name', ''),
(12, 'from_email', ''),
(13, 'notification_emails', ''),
(14, 'new_ticket_admin_email_subject', '[[CATEGORY_TITLE]-#[TICKET_ID]](New) [TICKET_SUBJECT]'),
(15, 'new_ticket_admin_email_body', '<p>User <strong>[NAME]</strong> has just submitted support ticket #[TICKET_ID]. </p>\r\n<p><strong>[TICKET_SUBJECT]</strong></p>\r\n<p>[TICKET_MESSAGE]</p>\r\n<p><a href="[BACKEND_LINK]">You can click here to view the ticket from back-end</a></p>\r\n<p><a href="[FRONTEND_LINK]">You can click here to view the ticket in the front-end</a></p>'),
(16, 'new_ticket_user_email_subject', '[[CATEGORY_TITLE]]Ticket #[TICKET_ID] received'),
(17, 'new_ticket_user_email_body', '<p>Dear <strong>[NAME]</strong></p>\r\n<p>We received your support request for ticket #[TICKET_ID]. Our support staff will check and reply you within 24 hours. Below are detail of your support ticket:</p>\r\n<p><strong>[TICKET_SUBJECT]</strong></p>\r\n<p>[TICKET_MESSAGE]</p>\r\n<p><a href="[FRONTEND_LINK]">You can click here to view the ticket</a> </p>\r\n<p><a href="[FRONTEND_LINK_WITHOUT_LOGIN]">You can also click here to view the ticket without having to login</a></p>'),
(18, 'ticket_updated_admin_email_subject', '[[CATEGORY_TITLE]]Ticket #[TICKET_ID] updated'),
(19, 'ticket_updated_admin_email_body', '<p>User [NAME] has just added a comment to ticket #[TICKET_ID] :</p>\r\n<p>[TICKET_COMMENT]</p>\r\n<p><a href="[FRONTEND_LINK]">You can click here to view the ticket from front-end</a></p>\r\n<p><a href="[BACKEND_LINK]">You can click here to view the ticket from back-end of your site</a></p>'),
(20, 'ticket_updated_user_email_subject', '[[CATEGORY_TITLE]]Ticket #[TICKET_ID] updated'),
(21, 'ticket_updated_user_email_body', '<p>Dear <strong>[NAME]</strong></p>\r\n<p>User <strong>[MANAGER_NAME]</strong> has just added a comment to ticket #<strong>[TICKET_ID]</strong>:</p>\r\n<p>[TICKET_COMMENT]</p>\r\n<p><a href="[FRONTEND_LINK]">You can click here to view the ticket</a></p>\r\n<p><a href="[FRONTEND_LINK_WITHOUT_LOGIN]">You can also click here to view the ticket without having to login</a></p>');
INSERT INTO `#__helpdeskpro_priorities` (`id`, `title`, `ordering`, `published`) VALUES
(1, '1 - VERY LOW', 1, 1),
(2, '2 - LOW', 2, 1),
(3, '3 - NORMAL', 3, 1),
(4, '4 - ELEVATED', 4, 1),
(5, '4 - ELEVATED', 5, 1),
(6, '5 - HIGH', 6, 1);
INSERT INTO `#__helpdeskpro_statuses` (`id`, `title`, `ordering`, `published`) VALUES
(1, 'NEW', 1, 1),
(2, 'PENDING RESOLUTION', 2, 1),
(3, 'REQUIRE FEEDBACK', 3, 1),
(4, 'CLOSED', 4, 1);
<?php
namespace Cms\Models;


class Comment extends \Speedy\Model\ActiveRecord {
	
	const PendingStatus	= 1;
	
	const ApprovedStatus= 2;
	
	const DisapprovedStatus	= 3;
	
	private static $_statuses = ['', 'Pending', 'Approved', 'Disapproved'];
	
}

?>
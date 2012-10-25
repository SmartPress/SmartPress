<?php
namespace Cms\Models;



use Speedy\Utility\Text;

class Comment extends \Speedy\Model\ActiveRecord {
	
	const PendingStatus	= 1;
	
	const ApprovedStatus= 2;
	
	const DisapprovedStatus	= 3;
	
	private static $_statuses = ['', 'Pending', 'Approved', 'Disapproved'];
	
	
	public function get_avatar() {
		$grav_url	= "http://www.gravatar.com/avatar/" . md5(strtolower(trim($this->author_email))) . 
			"?s=44";
		$html = "<img src=\"$grav_url\" height=\"44\" width=\"44\" alt=\"\" />";
		return $html;
	}
	
	public function get_created_at() {
		return $this->read_attribute('created_at')->format("F j, Y g:i a");
	}
	
	public function summary($more = '...', $allowable_tags = '', $maxLength = 200) {
		return Text::summarize($this->read_attribute('content',
					$more,
					true,
					$allowable_tags,
					$maxLength
				));
	}
	
}

?>
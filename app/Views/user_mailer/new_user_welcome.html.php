<h1>Welcome to the SmartPress Administration for <?php echo $this->domain; ?></h1>
<p>Someone has created a log in for you for the site at <?php echo $this->domain; ?>. 
	To access the site navigate your browser to 
	<a href="http://<?php echo $this->domain; ?>/admin">http://<?php echo $this->domain; ?>/admin</a> 
	and use the following login credentials:</p>
<table>
	<tr>
		<td align="right">Email:</td>
		<td><?php echo $this->user->email; ?></td>
	</tr>
	<tr>
		<td align="right">Password:</td>
		<td><?php echo $this->password; ?></td>
	</tr>
</table>
<p>Thank you,<br>
SmartPress Dev Team
</p>
<?php
namespace SmartPress\Models;


use Speedy\Enum;

class Permissions extends Enum {

	const Super = 128;

	const AdminWrite= 8;

	const AdminRead	= 4;

	const Write = 2;	// Basic write

	const Read 	= 1;	// Basic read

}
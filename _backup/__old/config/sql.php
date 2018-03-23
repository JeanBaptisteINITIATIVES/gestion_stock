<?php

// -----------------------------------------------------------------------
// Vérification si l'alias existe en bdd
// -----------------------------------------------------------------------
function checkIfAliasExist($alias)
{
	global $db;

	$req = $db->prepare('SELECT *
						 FROM allowed_users
						 WHERE alias = :alias');

	$req->bindParam(':alias', $alias, PDO::PARAM_STR);

	$req->execute();

	if ( !$req->fetch() )
	{
		return false;
	}
	else
	{
		return true;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification de l'existence de l'utilisateur à la connexion
// -----------------------------------------------------------------------
function checkIfUserExist($alias)
{
	global $db;

	$req = $db->prepare('SELECT au.alias
    					 FROM allowed_users AS au
    					 INNER JOIN users AS u
    					 ON au.id = u.allowed_users_id
    					 WHERE au.alias = :username');
	
	$req->bindParam(':username', $alias, PDO::PARAM_STR);
	
	$req->execute();

	if ( $req->fetch() )
	{
		return true;
	}
	else
	{ 
		return false;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Récupération du mot de passe de l'utilisateur
// -----------------------------------------------------------------------
function getUserPassword($user_alias)
{
	global $db;

	$req = $db->prepare('SELECT password
						 FROM allowed_users
						 WHERE alias = :user_alias');
	
	$req->bindParam(':user_alias', $user_alias, PDO::PARAM_STR);
	
	$req->execute();
	
	$result = $req->fetch();
	
	$req->closeCursor();

	return $result['password'];
}


// -----------------------------------------------------------------------
// Récupération du nom associé à l'alias
// -----------------------------------------------------------------------
function getUsernameByAlias($alias)
{
	global $db;

	$req = $db->prepare('SELECT name
						 FROM allowed_users
						 WHERE alias = :alias');

	$req->bindParam(':alias', $alias, PDO::PARAM_STR);

	$req->execute();

	$result = $req->fetch();

	$req->closeCursor();

	return $result['name'];
}


// -----------------------------------------------------------------------
// Récupération du nom associé à l'ID de l'utilisateur
// -----------------------------------------------------------------------
function getUsernameById($user_id)
{
	global $db;

	$req = $db->prepare('SELECT au.name AS username
						 FROM allowed_users AS au
						 INNER JOIN users AS u
						 ON au.id = u.allowed_users_id
						 WHERE u.id = :user_id');

	$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);

	$req->execute();

	$result = $req->fetch();

	$req->closeCursor();

	return $result['username'];
}


// -----------------------------------------------------------------------
// Insertion infos de connexion de l'utilisateur en bdd
// -----------------------------------------------------------------------
function insertNewUser($alias)
{
	global $db;

	$req = $db->prepare('INSERT INTO users(allowed_users_id, date_connection, online, last_activity)
					     SELECT allowed_users.id, NOW(), 1, NOW()
					     FROM allowed_users
					     WHERE allowed_users.id = (SELECT id
					     						   FROM allowed_users
					     						   WHERE alias = :username)');
    
    $req->bindParam(':username', $alias, PDO::PARAM_STR);
    
    $req->execute();

    $req->closeCursor();
}


// -----------------------------------------------------------------------
// Mise à jour infos de connexion utilisateur en bdd
// -----------------------------------------------------------------------
function updateUser($alias)
{
	global $db;

	$req = $db->prepare('UPDATE users
						 INNER JOIN allowed_users
						 ON users.allowed_users_id = allowed_users.id
						 SET date_connection = NOW(), online = 1, last_activity = NOW()
						 WHERE allowed_users_id = (SELECT id
						 						   FROM allowed_users
						 						   WHERE alias = :alias)
						 ');
			
	$req->bindParam(':alias', $alias, PDO::PARAM_STR);
	
	$req->execute();

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// déconnexion de l'utilisateur en bdd
// -----------------------------------------------------------------------
function disconnectUser($alias)
{
	global $db;

	$req = $db->prepare('UPDATE users
						 SET online = 0
						 WHERE allowed_users_id = (SELECT id
						 						   FROM allowed_users
						 						   WHERE alias = :alias)');
	
	$req->bindParam(':alias', $alias, PDO::PARAM_STR);
	
	$req->execute();

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Récupération de la dernière activité de l'utilisateur
// -----------------------------------------------------------------------
function getLastActivity($alias)
{
	global $db;

	$req = $db->prepare('SELECT UNIX_TIMESTAMP(last_activity)
						 FROM users
						 WHERE allowed_users_id = (SELECT id
						 						   FROM allowed_users
						 						   WHERE alias = :alias)');
	
	$req->bindParam(':alias', $alias, PDO::PARAM_STR);
	
	$req->execute();

	$result = $req->fetch();

	return $result['UNIX_TIMESTAMP(last_activity)'];

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Mise à jour dernière activité de l'utilisateur
// -----------------------------------------------------------------------
function updateLastActivity($alias)
{
	global $db;

	$req = $db->prepare('UPDATE users
						 SET last_activity = NOW()
						 WHERE allowed_users_id = (SELECT id
						 						   FROM allowed_users
						 						   WHERE alias = :alias)');
	
	$req->bindParam(':alias', $alias, PDO::PARAM_STR);
	
	$req->execute();

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si l'emplacement existe en bdd
// -----------------------------------------------------------------------
function checkIfLocationExist($location)
{
	global $db;

	$req = $db->prepare('SELECT num_location
					     FROM location
					     WHERE num_location = :location');

	$req->bindParam(':location', $location, PDO::PARAM_STR);

	$req->execute();

	if ( !$req->fetch() )
	{
		return false;
	}
	else
	{
		return true;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si la référence existe en bdd
// -----------------------------------------------------------------------
function checkIfReferenceExist($reference)
{
	global $db;

	$req = $db->prepare('SELECT reference
					     FROM products
					     WHERE reference = :reference');

	$req->bindParam(':reference', $reference, PDO::PARAM_STR);

	$req->execute();

	if ( !$req->fetch() )
	{
		return false;
	}
	else
	{
		return true;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si la désignation existe en bdd
// -----------------------------------------------------------------------
function checkIfDesignationExist($designation)
{
	global $db;

	$req = $db->prepare('SELECT designation
					     FROM products
					     WHERE designation = :designation');

	$req->bindParam(':designation', $designation, PDO::PARAM_STR);

	$req->execute();

	if ( !$req->fetch() )
	{
		return false;
	}
	else
	{
		return true;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si une entrée existe déjà à l'emplacement
// -----------------------------------------------------------------------
function checkIfLocationEmpty($location_id)
{
	global $db;

	$req = $db->prepare('SELECT COUNT(*) AS count
						 FROM stock_entry
						 WHERE location_id = :location_id');

	$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);

	$req->execute();

	$result = $req->fetch();

	if ( $result['count'] >= 1 ) // emplacement déjà occupé
	{
		return false;
	}
	else
	{
		return true;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si une entrée existe déjà à l'emplacement
// -----------------------------------------------------------------------
function checkIfLocationLocked($location_id)
{
	global $db;

	$req = $db->prepare('SELECT locked
						 FROM location
						 WHERE id = :location_id');

	$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);

	$req->execute();

	$result = $req->fetch();

	if ( $result['locked'] == 1 ) // emplacement bloqué
	{
		return true;
	}
	else
	{
		return false;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Blocage / déblocage d'emplacement
// -----------------------------------------------------------------------
function blockLocation($location_id, $locked)
{
	global $db;

	if ( $locked == 1 )
	{
		$req = $db->prepare('UPDATE location
						     SET locked = 0
						     WHERE id = :location_id');

		$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);

		$req->execute();
	}
	else
	{
		$req = $db->prepare('UPDATE location
						     SET locked = 1
						     WHERE id = :location_id');

		$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);

		$req->execute();
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Vérification si la saisie existe au même emplacement, même statut
// -----------------------------------------------------------------------
function checkIfEntryExist($location_id, $product_id, $status)
{
	global $db;

	$req = $db->prepare('SELECT COUNT(*) AS count
						 FROM stock_entry
						 WHERE location_id = :location_id
						 AND product_id = :product_id
						 AND status = :status');

	$req->bindParam(':location_id', $location_id, PDO::PARAM_INT);
	$req->bindParam(':product_id', $product_id, PDO::PARAM_INT);
	$req->bindParam(':status', $status, PDO::PARAM_STR);

	$req->execute();

	$result = $req->fetch();

	if ( $result['count'] >= 1 ) // Le produit existe déjà à cet emplacement
	{
		return true;
	}
	else
	{
		return false;
	}

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Récupération de l'ID de l'emplacement
// -----------------------------------------------------------------------
function getLocationId($num_location)
{
	global $db;

	$req = $db->prepare('SELECT id
						 FROM location
						 WHERE num_location = :num_location');

	$req->bindParam(':num_location', $num_location, PDO::PARAM_STR);

	$req->execute();

	$result = $req->fetch();

	return $result['id'];

	$req->closeCursor();;
}


// -----------------------------------------------------------------------
// Récupération de l'ID du produit
// -----------------------------------------------------------------------
function getProductId($reference, $designation)
{
	global $db;

	$req = $db->prepare('SELECT id
						 FROM products
						 WHERE reference = :reference
						 AND designation = :designation');

	$req->bindParam(':reference', $reference, PDO::PARAM_STR);
	$req->bindParam(':designation', $designation, PDO::PARAM_STR);

	$req->execute();

	$result = $req->fetch();

	return $result['id'];

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Récupération de l'ID de l'utilisateur
// -----------------------------------------------------------------------
function getUserId($alias)
{
	global $db;

	$req = $db->prepare('SELECT au.alias, u.id AS id
    					 FROM allowed_users AS au
    					 INNER JOIN users AS u
    					 ON au.id = u.allowed_users_id
    					 WHERE au.alias = :alias');

	$req->bindParam(':alias', $alias, PDO::PARAM_STR);

	$req->execute();

	$result = $req->fetch();

	return $result['id'];

	$req->closeCursor();
}


// -----------------------------------------------------------------------
// Insertion saisie stock de l'utilisateur
// -----------------------------------------------------------------------
function insertStockEntry($quantity, $status, $comment, $location, $product_id, $user_id)
{
	global $db;

	$req = $db->prepare('INSERT INTO stock_entry(location_id, product_id, quantity, status, comment, date_entry, date_update, user_id)
						 SELECT location.id, products.id, :quantity, :status, :comment, NOW(), NOW(), users.id
						 FROM location, products, users
						 WHERE location.num_location = :location
						 AND products.id = :product_id
						 AND users.id = :user_id');
    
    $req->bindParam(':quantity', $quantity, PDO::PARAM_INT);
	$req->bindParam(':status', $status, PDO::PARAM_STR);
	$req->bindParam(':comment', $comment, PDO::PARAM_STR);
	$req->bindParam(':location', $location, PDO::PARAM_STR);
	$req->bindParam(':product_id', $product_id, PDO::PARAM_INT);
	$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    $req->execute();

    $req->closeCursor();
}


// -----------------------------------------------------------------------
// Insertion saisie stock de l'utilisateur
// -----------------------------------------------------------------------
function insertFreeEntry($reference, $designation, $quantity, $status, $comment, $location, $user_id)
{
	global $db;

	$req = $db->prepare('INSERT INTO free_entry(location_id, reference, designation, quantity, status, comment, date_entry, date_update, user_id)
						 SELECT location.id, :reference, :designation, :quantity, :status, :comment, NOW(), NOW(), users.id
						 FROM location, users
						 WHERE location.num_location = :location
						 AND users.id = :user_id');
    
    $req->bindParam(':reference', $reference, PDO::PARAM_STR);
	$req->bindParam(':designation', $designation, PDO::PARAM_STR);
    $req->bindParam(':quantity', $quantity, PDO::PARAM_INT);
	$req->bindParam(':status', $status, PDO::PARAM_STR);
	$req->bindParam(':comment', $comment, PDO::PARAM_STR);
	$req->bindParam(':location', $location, PDO::PARAM_STR);
	$req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    $req->execute();

    $req->closeCursor();
}


// -----------------------------------------------------------------------
// Insertion infos logs
// -----------------------------------------------------------------------
function insertLog($old_loc, $new_loc, $reference, $designation, $old_qty, $new_qty, $status, $comment, $action, $user)
{
	global $db;

	$req = $db->prepare('INSERT INTO logs(old_loc, new_loc, reference, designation, old_qty, new_qty, status, comment, action, date_action, user)
						 VALUES (:old_loc, :new_loc, :reference, :designation, :old_qty, :new_qty, :status, :comment, :action, NOW(), :user)');

	$req->bindParam(':old_loc', $old_loc, PDO::PARAM_STR);
	$req->bindParam(':new_loc', $new_loc, PDO::PARAM_STR);
	$req->bindParam(':reference', $reference, PDO::PARAM_STR);
	$req->bindParam(':designation', $designation, PDO::PARAM_STR);
	$req->bindParam(':old_qty', $old_qty, PDO::PARAM_INT);
	$req->bindParam(':new_qty', $new_qty, PDO::PARAM_INT);
	$req->bindParam(':status', $status, PDO::PARAM_STR);
	$req->bindParam(':comment', $comment, PDO::PARAM_STR);
	$req->bindParam(':action', $action, PDO::PARAM_STR);
	$req->bindParam(':user', $user, PDO::PARAM_STR);

	$req->execute();

    $req->closeCursor();
}


// -----------------------------------------------------------------------
// Récupération de la liste des produits enlogés
// -----------------------------------------------------------------------
function getStorageProducts()
{
	global $db;

	$list_products = array();

	$req1 = $db->prepare('SELECT s.stock_id AS id, s.quantity AS quantity, s.status AS status, s.comment AS comment, DATE_FORMAT(s.date_entry, "%Y/%m/%d %H:%i:%s") AS date_entry, DATE_FORMAT(s.date_update, "%Y/%m/%d %H:%i:%s") AS date_update, s.user_id AS user_id, l.num_location AS num_location, l.locked AS locked, p.reference AS reference, p.designation AS designation, p.stock AS stock, u.id AS user_id
					      FROM stock_entry AS s
					      INNER JOIN location AS l
					      ON s.location_id = l.id
					      INNER JOIN products AS p
					      ON s.product_id = p.id
					      INNER JOIN users AS u
					      ON s.user_id = u.id');
	
	$req1->execute();
	
	while ( $row = $req1->fetch() )
	{
		$list_products[] = $row;
	}
	
	$req1->closeCursor();

	$req2 = $db->prepare('SELECT f.free_id AS id, f.reference AS reference, f.designation AS designation, f.quantity AS quantity, f.status AS status, f.comment AS comment, DATE_FORMAT(f.date_entry, "%Y/%m/%d %H:%i:%s") AS date_entry, DATE_FORMAT(f.date_update, "%Y/%m/%d %H:%i:%s") AS date_update, f.user_id AS user_id, f.stock AS stock, l.num_location AS num_location, l.locked AS locked, u.id AS user_id
					      FROM free_entry AS f
					      INNER JOIN location AS l
					      ON f.location_id = l.id
					      INNER JOIN users AS u
					      ON f.user_id = u.id');
	
	$req2->execute();
	
	while ( $row = $req2->fetch() )
	{
		$list_products[] = $row;
	}
	
	$req2->closeCursor();

	return $list_products;
}




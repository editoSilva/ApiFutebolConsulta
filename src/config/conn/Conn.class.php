<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe abstrata de conexão.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2017, Edilson Moura @ INVIZZA INTERATIVA
 */
abstract class Conn {

	private static $Host = 'localhost';
	private static $User = 'root';
	private static $Pass = 'senha_da_nasa';
	private static $Dbsa = 'futebol';

	/** @var PDO */
	private static $Connect = null;


	/**
	 * Conecta com o banco de dados com o pattern singleton.
	 * Retorna um objeto PDO!
	 */
	private static
	function Conectar() {
		try {
			if ( self::$Connect == null ):
				$dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
			$options = [ PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' ];
			self::$Connect = new PDO( $dsn, self::$User, self::$Pass, $options );
			endif;
		} catch ( PDOException $e ) {
			PHPErro( $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine() );
			die;
		}

		self::$Connect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		return self::$Connect;
	}

	/** Retorna um objeto PDO Singleton Pattern. */
	protected static
	function getConn() {
		return self::Conectar();
	}

}
<?php

$pdo = new PDO(dsn: 'sqlite::memory:');

$result = $pdo->query(query: "SELECT json_valid('{\"a\":1}')")->fetchColumn();

var_dump(value: $result);
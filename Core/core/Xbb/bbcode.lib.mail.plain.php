<?php

/******************************************************************************
 *                                                                            *
 *   bbcode.lib.php, v 0.24 2007/03/06 - This is part of xBB library          *
 *   Copyright (C) 2006-2007  Dmitriy Skorobogatov  dima@pc.uz                *
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify     *
 *   it under the terms of the GNU General Public License as published by     *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                      *
 *                                                                            *
 *   This program is distributed in the hope that it will be useful,          *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of           *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            *
 *   GNU General Public License for more details.                             *
 *                                                                            *
 *   You should have received a copy of the GNU General Public License        *
 *   along with this program; if not, write to the Free Software              *
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA *
 *                                                                            *
 ******************************************************************************/

class mail_plain_bbcode {
	/* Описания свойств и методов смотрите в документации. */
	var $tag = '';
	var $attrib = array();
	var $text = '';
	var $syntax = array();
	var $tree = array();
	var $tags = array(
		'*'       => 'mail_plain_bb_li',
		'a'       => 'mail_plain_bb_a',
		'abbr'    => 'mail_plain_bb_abbr',
		'acronym' => 'mail_plain_bb_acronym',
		'address' => 'mail_plain_bb_address',
		'align'   => 'mail_plain_bb_align',
		'anchor'  => 'mail_plain_bb_a',
		'b'       => 'mail_plain_bb_strong',
		'bbcode'  => 'mail_plain_bb_bbcode',
		'caption' => 'mail_plain_bb_caption',
		'center'  => 'mail_plain_bb_align',
		'code'    => 'mail_plain_bb_code',
		'color'   => 'mail_plain_bb_color',
		'email'   => 'mail_plain_bb_email',
		'font'    => 'mail_plain_bb_font',
		'google'  => 'mail_plain_bb_google',
		'h1'      => 'mail_plain_bb_h1',
		'h2'      => 'mail_plain_bb_h2',
		'h3'      => 'mail_plain_bb_h3',
		'hr'      => 'mail_plain_bb_hr',
		'i'       => 'mail_plain_bb_i',
		'img'     => 'mail_plain_bb_img',
		'justify' => 'mail_plain_bb_align',
		'left'    => 'mail_plain_bb_align',
		'list'    => 'mail_plain_bb_list',
		'nobb'    => 'mail_plain_bb_nobb',
		'php'     => 'mail_plain_bb_php',
		'quote'   => 'mail_plain_bb_quote',
		'right'   => 'mail_plain_bb_align',
		's'       => 'mail_plain_bb_del',
		'size'    => 'mail_plain_bb_size',
		'strike'  => 'mail_plain_bb_del',
		'sub'     => 'mail_plain_bb_sub',
		'sup'     => 'mail_plain_bb_sup',
		'table'   => 'mail_plain_bb_table',
		'td'      => 'mail_plain_bb_td',
		'th'      => 'mail_plain_bb_th',
		'tr'      => 'mail_plain_bb_tr',
		'tt'      => 'mail_plain_bb_tt',
		'u'       => 'mail_plain_bb_u',
		'url'     => 'mail_plain_bb_a'
	);
	var $children = array(
		'a','abbr','acronym','address','align','anchor','b','bbcode','center',
		'code','color','email','font','google','h1','h2','h3','hr','i','img',
		'justify','left','list','nobb','php','quote','right','s','size',
		'strike','sub','sup','table','tt','u','url'
	);
	var $mnemonics = array();
	var $autolinks = true;
	var $is_close = false;
	var $lbr = 0;
	var $rbr = 0;

	function mail_plain_bbcode($code = '') {
		if (is_array($code)) {
			$is_tree = false;
			foreach ($code as $key => $val) {
				if (isset($val['val'])) {
					$this -> tree = $code;
					$this -> syntax = $this -> get_syntax();
					$is_tree = true;
					break;
				}
			}
			if (! $is_tree) {
				$this -> syntax = $code;
				$this -> get_tree();
			}
			$this -> text = '';
			foreach ($this -> syntax as $val) {
				$this -> text .= $val['str'];
			}
		} elseif ($code) {
			$this -> text = $code;
			$this -> parse();
		}
	}

	function get_tokens() {
		$length = strlen($this -> text);
		$tokens = array();
		$token_key = -1;
		$type_of_char = null;
		for ($i=0; $i < $length; ++$i) {
			$previous_type = $type_of_char;
			switch ($this -> text{$i}) {
				case '[':
					$type_of_char = 0;
					break;
				case ']':
					$type_of_char = 1;
					break;
				case '"':
					$type_of_char = 2;
					break;
				case "'":
					$type_of_char = 3;
					break;
				case "=":
					$type_of_char = 4;
					break;
				case '/':
					$type_of_char = 5;
					break;
				case ' ':
					$type_of_char = 6;
					break;
				case "\t":
					$type_of_char = 6;
					break;
				case "\n":
					$type_of_char = 6;
					break;
				case "\r":
					$type_of_char = 6;
					break;
				case "\0":
					$type_of_char = 6;
					break;
				case "\x0B":
					$type_of_char = 6;
					break;
				default:
					$type_of_char = 7;
			}
			if (7 == $previous_type && $type_of_char != $previous_type) {
				$word = strtolower($tokens[$token_key][1]);
				if (isset($this -> tags[$word])) {
					$tokens[$token_key][0] = 8;
				}
			}
			switch ($type_of_char) {
				case 6:
					if (6 == $previous_type) {
						$tokens[$token_key][1] .= $this -> text{$i};
					} else {
						$tokens[++$token_key] = array(6, $this -> text{$i});
					}
					break;
				case 7:
					if (7 == $previous_type) {
						$tokens[$token_key][1] .= $this -> text{$i};
					} else {
						$tokens[++$token_key] = array(7, $this -> text{$i});
					}
					break;
				default:
					$tokens[++$token_key] = array(
						$type_of_char, $this -> text{$i}
					);
			}
		}
		return $tokens;
	}

	function parse($code = '') {
		if ($code) {
			$this -> mail_plain_bbcode($code);
			return;
		}
		/*
		Используем метод конечных автоматов
		Список возможных состояний автомата:
		0  - Начало сканирования или находимся вне тега. Ожидаем что угодно.
		1  - Встретили символ "[", который считаем началом тега. Ожидаем имя
			 тега, или символ "/".
		2  - Нашли в теге неожидавшийся символ "[". Считаем предыдущую строку
			 ошибкой. Ожидаем имя тега, или символ "/".
		3  - Нашли в теге синтаксическую ошибку. Текущий символ не является "[".
			 Ожидаем что угодно.
		4  - Сразу после "[" нашли символ "/". Предполагаем, что попали в
			 закрывающий тег. Ожидаем имя тега или символ "]".
		5  - Сразу после "[" нашли имя тега. Считаем, что находимся в
			 открывающем теге. Ожидаем пробел или "=" или "/" или "]".
		6  - Нашли завершение тега "]". Ожидаем что угодно.
		7  - Сразу после "[/" нашли имя тега. Ожидаем "]".
		8  - В открывающем теге нашли "=". Ожидаем пробел или значение атрибута.
		9  - В открывающем теге нашли "/", означающий закрытие тега. Ожидаем
			 "]".
		10 - В открывающем теге нашли пробел после имени тега или имени
			 атрибута. Ожидаем "=" или имя другого атрибута или "/" или "]".
		11 - Нашли '"' начинающую значение атрибута, ограниченное кавычками.
			 Ожидаем что угодно.
		12 - Нашли "'" начинающий значение атрибута, ограниченное апострофами.
			 Ожидаем что угодно.
		13 - Нашли начало незакавыченного значения атрибута. Ожидаем что угодно.
		14 - В открывающем теге после "=" нашли пробел. Ожидаем значение
			 атрибута.
		15 - Нашли имя атрибута. Ожидаем пробел или "=" или "/" или "]".
		16 - Находимся внутри значения атрибута, ограниченного кавычками.
			 Ожидаем что угодно.
		17 - Завершение значения атрибута. Ожидаем пробел или имя следующего
			 атрибута или "/" или "]".
		18 - Находимся внутри значения атрибута, ограниченного апострофами.
			 Ожидаем что угодно.
		19 - Находимся внутри незакавыченного значения атрибута. Ожидаем что
			 угодно.
		20 - Нашли пробел после значения атрибута. Ожидаем имя следующего
			 атрибута или "/" или "]".

		Описание конечного автомата:
		*/
		$finite_automaton = array(
			   // Предыдущие |   Состояния для текущих событий (лексем)   |
			   //  состояния |  0 |  1 |  2 |  3 |  4 |  5 |  6 |  7 |  8 |
				   0 => array(  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 )
				,  1 => array(  2 ,  3 ,  3 ,  3 ,  3 ,  4 ,  3 ,  3 ,  5 )
				,  2 => array(  2 ,  3 ,  3 ,  3 ,  3 ,  4 ,  3 ,  3 ,  5 )
				,  3 => array(  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 )
				,  4 => array(  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  7 )
				,  5 => array(  2 ,  6 ,  3 ,  3 ,  8 ,  9 , 10 ,  3 ,  3 )
				,  6 => array(  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 )
				,  7 => array(  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 )
				,  8 => array( 13 , 13 , 11 , 12 , 13 , 13 , 14 , 13 , 13 )
				,  9 => array(  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 )
				, 10 => array(  2 ,  6 ,  3 ,  3 ,  8 ,  9 ,  3 , 15 , 15 )
				, 11 => array( 16 , 16 , 17 , 16 , 16 , 16 , 16 , 16 , 16 )
				, 12 => array( 18 , 18 , 18 , 17 , 18 , 18 , 18 , 18 , 18 )
				, 13 => array( 19 ,  6 , 19 , 19 , 19 , 19 , 17 , 19 , 19 )
				, 14 => array(  2 ,  3 , 11 , 12 , 13 , 13 ,  3 , 13 , 13 )
				, 15 => array(  2 ,  6 ,  3 ,  3 ,  8 ,  9 , 10 ,  3 ,  3 )
				, 16 => array( 16 , 16 , 17 , 16 , 16 , 16 , 16 , 16 , 16 )
				, 17 => array(  2 ,  6 ,  3 ,  3 ,  3 ,  9 , 20 , 15 , 15 )
				, 18 => array( 18 , 18 , 18 , 17 , 18 , 18 , 18 , 18 , 18 )
				, 19 => array( 19 ,  6 , 19 , 19 , 19 , 19 , 20 , 19 , 19 )
				, 20 => array(  2 ,  6 ,  3 ,  3 ,  3 ,  9 ,  3 , 15 , 15 )
			);
		// Закончили описание конечного автомата
		$mode = 0;
		$result = array();
		$tag_decomposition = array();
		$token_key = -1;
		$value = '';
		// Сканируем массив лексем с помощью построенного автомата:
		foreach ($this -> get_tokens() as $token) {
			$previous_mode = $mode;
			$mode = $finite_automaton[$previous_mode][$token[0]];
			switch ($mode) {
				case 0:
					if (-1 < $token_key && 'text'==$result[$token_key]['type']) {
						$result[$token_key]['str'] .= $token[1];
					} else {
						$result[++$token_key] = array(
								'type' => 'text',
								'str' => $token[1]
							);
					}
					break;
				case 1:
					$tag_decomposition['name']     = '';
					$tag_decomposition['type']     = '';
					$tag_decomposition['str']      = '[';
					$tag_decomposition['layout'][] = array( 0, '[' );
					break;
				case 2:
					if (-1<$token_key && 'text'==$result[$token_key]['type']) {
						$result[$token_key]['str'] .= $tag_decomposition['str'];
					} else {
						$result[++$token_key] = array(
								'type' => 'text',
								'str' => $tag_decomposition['str']
							);
					}
					$tag_decomposition = array();
					$tag_decomposition['name']     = '';
					$tag_decomposition['type']     = '';
					$tag_decomposition['str']      = '[';
					$tag_decomposition['layout'][] = array( 0, '[' );
					break;
				case 3:
					if (-1<$token_key && 'text'==$result[$token_key]['type']) {
						$result[$token_key]['str'] .= $tag_decomposition['str'];
						$result[$token_key]['str'] .= $token[1];
					} else {
						$result[++$token_key] = array(
								'type' => 'text',
								'str' => $tag_decomposition['str'].$token[1]
							);
					}
					$tag_decomposition = array();
					break;
				case 4:
					$tag_decomposition['type'] = 'close';
					$tag_decomposition['str'] .= '/';
					$tag_decomposition['layout'][] = array( 1, '/' );
					break;
				case 5:
					$tag_decomposition['type'] = 'open';
					$name = strtolower($token[1]);
					$tag_decomposition['name'] = $name;
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 2, $token[1] );
					$tag_decomposition['attrib'][$name] = '';
					break;
				case 6:
					if (! isset($tag_decomposition['name'])) {
						$tag_decomposition['name'] = '';
					}
					if (13 == $previous_mode || 19 == $previous_mode) {
						$tag_decomposition['layout'][] = array( 7, $value );
					}
					$tag_decomposition['str'] .= ']';
					$tag_decomposition['layout'][] = array( 0, ']' );
					$result[++$token_key] = $tag_decomposition;
					$tag_decomposition = array();
					break;
				case 7:
					$tag_decomposition['name'] = strtolower($token[1]);
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 2, $token[1] );
					break;
				case 8:
					$tag_decomposition['str'] .= '=';
					$tag_decomposition['layout'][] = array( 3, '=' );
					break;
				case 9:
					$tag_decomposition['type'] = 'open/close';
					$tag_decomposition['str'] .= '/';
					$tag_decomposition['layout'][] = array( 1, '/' );
					break;
				case 10:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 4, $token[1] );
					break;
				case 11:
					$tag_decomposition['str'] .= '"';
					$tag_decomposition['layout'][] = array( 5, '"' );
					$value = '';
					break;
				case 12:
					$tag_decomposition['str'] .= "'";
					$tag_decomposition['layout'][] = array( 5, "'" );
					$value = '';
					break;
				case 13:
					$tag_decomposition['attrib'][$name] = $token[1];
					$value = $token[1];
					$tag_decomposition['str'] .= $token[1];
					break;
				case 14:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 4, $token[1] );
					break;
				case 15:
					$name = strtolower($token[1]);
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 6, $token[1] );
					$tag_decomposition['attrib'][$name] = '';
					break;
				case 16:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['attrib'][$name] .= $token[1];
					$value .= $token[1];
					break;
				case 17:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['layout'][] = array( 7, $value );
					$value = '';
					$tag_decomposition['layout'][] = array( 5, $token[1] );
					break;
				case 18:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['attrib'][$name] .= $token[1];
					$value .= $token[1];
					break;
				case 19:
					$tag_decomposition['str'] .= $token[1];
					$tag_decomposition['attrib'][$name] .= $token[1];
					$value .= $token[1];
					break;
				case 20:
					$tag_decomposition['str'] .= $token[1];
					if ( 13 == $previous_mode || 19 == $previous_mode ) {
						$tag_decomposition['layout'][] = array( 7, $value );
					}
					$value = '';
					$tag_decomposition['layout'][] = array( 4, $token[1] );
					break;
			}
		}
		if (count($tag_decomposition)) {
			if ( -1 < $token_key && 'text' == $result[$token_key]['type'] ) {
				$result[$token_key]['str'] .= $tag_decomposition['str'];
			} else {
				$result[++$token_key] = array(
						'type' => 'text',
						'str' => $tag_decomposition['str']
					);
			}
		}
		$this -> syntax = $result;
		$this -> get_tree();
		return $result;
	}

	function specialchars($string) {
		$chars = array(
			'[' => '@l;',
			']' => '@r;',
			'"' => '@q;',
			"'" => '@a;',
			'@' => '@at;'
		);
		return strtr($string, $chars);
	}

	function unspecialchars($string) {
		$chars = array(
			'@l;'  => '[',
			'@r;'  => ']',
			'@q;'  => '"',
			'@a;'  => "'",
			'@at;' => '@'
		);
		return strtr($string, $chars);
	}

	function must_close_tag($current, $next) {
		$class_vars = get_class_vars($this -> tags[$current]);
		$must_close = in_array($next, $class_vars['ends']);
		$class_vars = get_class_vars($this -> tags[$next]);
		if (! $must_close && isset($class_vars['stop'])) {
			$must_close = in_array($current, $class_vars['stop']);
		}
		return $must_close;
	}

	function normalize_bracket($syntax) {
		$structure = array();
		$structure_key = -1;
		$level = 0;
		$open_tags = array();
		foreach ($syntax as $syntax_key => $val) {
			unset($val['layout']);
			switch ($val['type']) {
				case 'text':
					$val['str'] = $this -> unspecialchars($val['str']);
					$type = (-1 < $structure_key)
						? $structure[$structure_key]['type'] : false;
					if ('text' == $type) {
						$structure[$structure_key]['str'] .= $val['str'];
					} else {
						$structure[++$structure_key] = $val;
						$structure[$structure_key]['level'] = $level;
					}
					break;
				case 'open/close':
					$val['attrib'] = array_map(
						array(&$this, 'unspecialchars'), $val['attrib']
					);
					foreach (array_reverse($open_tags,true) as $ult_key => $ultimate) {
						if ($this -> must_close_tag($ultimate, $val['name'])) {
							$structure[++$structure_key] = array(
									'type'  => 'close',
									'name'  => $ultimate,
									'str'   => '',
									'level' => --$level
								);
							unset($open_tags[$ult_key]);
						} else {
							break;
						}
					}
					$structure[++$structure_key] = $val;
					$structure[$structure_key]['level'] = $level;
					break;
				case 'open':
					$val['attrib'] = array_map(
						array(&$this, 'unspecialchars'), $val['attrib']
					);
					foreach (array_reverse($open_tags,true) as $ult_key => $ultimate) {
						if ($this -> must_close_tag($ultimate, $val['name'])) {
							$structure[++$structure_key] = array(
									'type'  => 'close',
									'name'  => $ultimate,
									'str'   => '',
									'level' => --$level
								);
							unset($open_tags[$ult_key]);
						} else { break; }
					}
					$class_vars = get_class_vars($this -> tags[$val['name']]);
					if ($class_vars['is_close']) {
						$val['type'] = 'open/close';
						$structure[++$structure_key] = $val;
						$structure[$structure_key]['level'] = $level;
					} else {
						$structure[++$structure_key] = $val;
						$structure[$structure_key]['level'] = $level++;
						$open_tags[] = $val['name'];
					}
					break;
				case 'close':
					if (! count($open_tags)) {
						$type = (-1 < $structure_key)
							? $structure[$structure_key]['type'] : false;
						if ( 'text' == $type ) {
							$structure[$structure_key]['str'] .= $val['str'];
						} else {
							$structure[++$structure_key] = array(
									'type'  => 'text',
									'str'   => $val['str'],
									'level' => 0
								);
						}
						break;
					}
					if (! $val['name']) {
						end($open_tags);
						list($ult_key, $ultimate) = each($open_tags);
						$val['name'] = $ultimate;
						$structure[++$structure_key] = $val;
						$structure[$structure_key]['level'] = --$level;
						unset($open_tags[$ult_key]);
						break;
					}
					if (! in_array($val['name'],$open_tags)) {
						$type = (-1 < $structure_key)
							? $structure[$structure_key]['type'] : false;
						if ('text' == $type) {
							$structure[$structure_key]['str'] .= $val['str'];
						} else {
							$structure[++$structure_key] = array(
									'type'  => 'text',
									'str'   => $val['str'],
									'level' => $level
								);
						}
						break;
					}
					foreach (array_reverse($open_tags,true) as $ult_key => $ultimate) {
						if ($ultimate != $val['name']) {
							$structure[++$structure_key] = array(
									'type'  => 'close',
									'name'  => $ultimate,
									'str'   => '',
									'level' => --$level
								);
							unset($open_tags[$ult_key]);
						} else {
							break;
						}
					}
					$structure[++$structure_key] = $val;
					$structure[$structure_key]['level'] = --$level;
					unset($open_tags[$ult_key]);
			}
		}
		foreach (array_reverse($open_tags,true) as $ult_key => $ultimate) {
			$structure[++$structure_key] = array(
					'type'  => 'close',
					'name'  => $ultimate,
					'str'   => '',
					'level' => --$level
				);
			unset($open_tags[$ult_key]);
		}
		return $structure;
	}

	function get_tree() {
		/* Превращаем $this -> syntax в правильную скобочную структуру */
		$structure = $this -> normalize_bracket($this -> syntax);
		/* Отслеживаем, имеют ли элементы неразрешенные подэлементы.
		   Соответственно этому исправляем $structure. */
		$normalized = array();
		$normal_key = -1;
		$level = 0;
		$open_tags = array();
		$not_tags = array();
		foreach ($structure as $structure_key => $val) {
			switch ($val['type']) {
				case 'text':
					$type = (-1 < $normal_key)
						? $normalized[$normal_key]['type'] : false;
					if ('text' == $type) {
						$normalized[$normal_key]['str'] .= $val['str'];
					} else {
						$normalized[++$normal_key] = $val;
						$normalized[$normal_key]['level'] = $level;
					}
					break;
				case 'open/close':
					$is_open = count($open_tags);
					end($open_tags);
					$info = get_class_vars($this -> tags[$val['name']]);
					if ($is_open) {
						$class_vars = get_class_vars(
							$this -> tags[current($open_tags)]
						);
						$children = $class_vars['children'];
					} else {
						$children = array();
					}
					if (isset($info['top_level'])) {
						$top_level = $info['top_level'];
					} else {
						$top_level = in_array($val['name'], $this -> children);
					}
					$is_child = in_array($val['name'], $children);
					if (isset($info['parent']) && ! $is_child) {
						if (in_array(current($open_tags), $info['parent'])) {
							$is_child = true;
						}
					}
					if (! $level && ! $top_level || $is_open && ! $is_child) {
						$type = (-1 < $normal_key)
							? $normalized[$normal_key]['type'] : false;
						if ( 'text' == $type ) {
							$normalized[$normal_key]['str'] .= $val['str'];
						} else {
							$normalized[++$normal_key] = array(
									'type'  => 'text',
									'str'   => $val['str'],
									'level' => $level
								);
						}
						break;
					}
					$normalized[++$normal_key] = $val;
					$normalized[$normal_key]['level'] = $level;
					break;
				case 'open':
					$is_open = count($open_tags);
					end($open_tags);
					$info = get_class_vars($this -> tags[$val['name']]);
					if ($is_open) {
						$class_vars = get_class_vars(
							$this -> tags[current($open_tags)]
						);
						$children = $class_vars['children'];
					} else {
						$children = array();
					}
					if (isset($info['top_level'])) {
						$top_level = $info['top_level'];
					} else {
						$top_level = in_array($val['name'], $this -> children);
					}
					$is_child = in_array($val['name'], $children);
					if (isset($info['parent']) && ! $is_child) {
						if (in_array(current($open_tags), $info['parent'])) {
							$is_child = true;
						}
					}
					if (! $level && ! $top_level || $is_open && ! $is_child) {
						$not_tags[$val['level']] = $val['name'];
						$type = (-1 < $normal_key)
							? $normalized[$normal_key]['type'] : false;
						if ( 'text' == $type ) {
							$normalized[$normal_key]['str'] .= $val['str'];
						} else {
							$normalized[++$normal_key] = array(
									'type'  => 'text',
									'str'   => $val['str'],
									'level' => $level
								);
						}
						break;
					}
					$normalized[++$normal_key] = $val;
					$normalized[$normal_key]['level'] = $level++;
					$ult_key = count($open_tags);
					$open_tags[$ult_key] = $val['name'];
					break;
				case 'close':
					$not_normal = isset($not_tags[$val['level']])
						&& $not_tags[$val['level']] = $val['name'];
					if ( $not_normal ) {
						unset($not_tags[$val['level']]);
						$type = (-1 < $normal_key)
							? $normalized[$normal_key]['type'] : false;
						if ( 'text' == $type ) {
							$normalized[$normal_key]['str'] .= $val['str'];
						} else {
							$normalized[++$normal_key] = array(
									'type'  => 'text',
									'str'   => $val['str'],
									'level' => $level
								);
						}
						break;
					}
					$normalized[++$normal_key] = $val;
					$normalized[$normal_key]['level'] = --$level;
					$ult_key = count($open_tags) - 1;
					unset($open_tags[$ult_key]);
					break;
			}
		}
		unset($structure);
		// Формируем дерево элементов
		$result = array();
		$result_key = -1;
		$open_tags = array();
		$val_key = -1;
		foreach ($normalized as $normal_key => $val) {
			switch ($val['type']) {
				case 'text':
					if (! $val['level']) {
						$result[++$result_key] = array(
								'type' => 'text',
								'str' => $val['str']
							);
						break;
					}
					$open_tags[$val['level']-1]['val'][] = array(
							'type' => 'text',
							'str' => $val['str']
						);
					break;
				case 'open/close':
					if (! $val['level']) {
						$result[++$result_key] = array(
								'type'   => 'item',
								'name'   => $val['name'],
								'attrib' => $val['attrib'],
								'val'    => array()
							);
						break;
					}
					$open_tags[$val['level']-1]['val'][] = array(
							'type'   => 'item',
							'name'   => $val['name'],
							'attrib' => $val['attrib'],
							'val'    => array()
						);
					break;
				case 'open':
					$open_tags[$val['level']] = array(
							'type'   => 'item',
							'name'   => $val['name'],
							'attrib' => $val['attrib'],
							'val'    => array()
						);
					break;
				case 'close':
					if ( ! $val['level'] ) {
						$result[++$result_key] = $open_tags[0];
						unset($open_tags[0]);
						break;
					}
					$open_tags[$val['level']-1]['val'][] = $open_tags[$val['level']];
					unset($open_tags[$val['level']]);
					break;
			}
		}
		$this -> tree = $result;
		return $result;
	}

	function get_syntax($tree = false) {
		if (! is_array($tree)) {
			$tree = $this -> tree;
		}
		$syntax = array();
		foreach ($tree as $elem) {
			if ('text' == $elem['type']) {
				$syntax[] = array(
					'type' => 'text',
					'str' => $this -> specialchars($elem['str'])
				);
			} else {
				$sub_elems = $this -> get_syntax($elem['val']);
				$str = '';
				$layout = array(array(0, '['));
				foreach ($elem['attrib'] as $name => $val) {
					$val = $this -> specialchars($val);
					if ($str) {
						$str .= ' ';
						$layout[] = array(4, ' ');
						$layout[] = array(6, $name);
					} else {
						$layout[] = array(2, $name);
					}
					$str .= $name;
					if ($val) {
						$str .= '="'.$val.'"';
						$layout[] = array(3, '=');
						$layout[] = array(5, '"');
						$layout[] = array(7, $val);
						$layout[] = array(5, '"');
					}
				}
				if (count($sub_elems)) {
					$str = '['.$str.']';
				} else {
					$str = '['.$str.' /]';
					$layout[] = array(4, ' ');
					$layout[] = array(1, '/');
				}
				$layout[] = array(0, ']');
				$syntax[] = array(
					'type' => count($sub_elems) ? 'open' : 'open/close',
					'str' => $str,
					'name' => $elem['name'],
					'attrib' => $elem['attrib'],
					'layout' => $layout
				);
				foreach ($sub_elems as $sub_elem) { $syntax[] = $sub_elem; }
				if (count($sub_elems)) {
					$syntax[] = array(
						'type' => 'close',
						'str' => '[/'.$elem['name'].']',
						'name' => $elem['name'],
						'layout' => array(
							array(0, '['),
							array(1, '/'),
							array(2, $elem['name']),
							array(0, ']')
						)
					);
				}
			}
		}
		return $syntax;
	}

	function insert_smiles($text) {
		/**
		* on this step convetr \n to <br />
		*/
		return $text;
		
		$text = nl2br(htmlspecialchars($text,ENT_NOQUOTES));
		$text = str_replace('  ', '&nbsp;&nbsp;', $text);
		if ($this -> autolinks) {
			$uri = "[\w\d-]+\.[\w\d-]+[^\s<\"\']*[^.,;\s<\"\'\)]+";
			$search = array(
				"'(.)((http|https|ftp)://".$uri.")'si",
				"'([^/])(www\.".$uri.")'si",
				"'([^\w\d-\.])([\w\d-\.]+@[\w\d-\.]+\.[\w]+[^.,;\s<\"\'\)]+)'si"
			);
			$replace = array(
				'$1<a href="$2" target="_blank">$2</a>',
				'$1<a href="http://$2" target="_blank">$2</a>',
				'$1<a href="mailto:$2">$2</a>'
			);
			$text = preg_replace($search, $replace, $text);
		}
		foreach ($this -> mnemonics as $mnemonic => $value) {
			$text = str_replace($mnemonic, $value, $text);
		}
		return $text;
	}

	function highlight() {
		$chars = array(
			'@l;'  => '<span class="mail_plain_bb_spec_char">@l;</span>',
			'@r;'  => '<span class="mail_plain_bb_spec_char">@r;</span>',
			'@q;'  => '<span class="mail_plain_bb_spec_char">@q;</span>',
			'@a;'  => '<span class="mail_plain_bb_spec_char">@a;</span>',
			'@at;' => '<span class="mail_plain_bb_spec_char">@at;</span>'
		);
		$uri = "[\w\d-]+\.[\w\d-]+[^\s<\"\']*[^.,;\s<\"\'\)]+";
		$search = array(
			"'(.)((http|https|ftp)://".$uri.")'si",
			"'([^/])(www\.".$uri.")'si",
			"'([^\w\d-\.])([\w\d-\.]+@[\w\d-\.]+\.[\w]+[^.,;\s<\"\'\)]+)'si"
		);
		$replace = array(
			'$1<span class="mail_plain_bb_autolink">$2</span>',
			'$1<span class="mail_plain_bb_autolink">$2</span>',
			'$1<span class="mail_plain_bb_autolink">$2</span>'
		);
		$str = '';
		foreach($this -> syntax as $elem) {
			if ('text' == $elem['type']) {
				$elem['str'] = strtr(htmlspecialchars($elem['str']), $chars);
				foreach ($this -> mnemonics as $mnemonic => $value) {
					$elem['str'] = str_replace(
						$mnemonic,
						'<span class="mail_plain_bb_mnemonic">'.$mnemonic.'</span>',
						$elem['str']
					);
				}
				$elem['str'] = preg_replace($search, $replace, $elem['str']);
				$str .= $elem['str'];
			} else {
				$str .= '<span class="mail_plain_bb_tag">';
				foreach ($elem['layout'] as $val) {
					switch ($val[0]) {
						case 0:
							$str .= '<span class="mail_plain_bb_bracket">'.$val[1]
								.'</span>';
							break;
						case 1:
							$str .= '<span class="mail_plain_bb_slash">/</span>';
							break;
						case 2:
							$str .= '<span class="mail_plain_bb_tagname">'.$val[1]
								.'</span>';
							break;
						case 3:
							$str .= '<span class="mail_plain_bb_equal">=</span>';
							break;
						case 4:
							$str .= $val[1];
							break;
						case 5:
							if (! trim($val[1])) {
								$str .= $val[1];
							} else {
								$str .= '<span class="mail_plain_bb_quote">'.$val[1]
									.'</span>';
							}
							break;
						case 6:
							$str .= '<span class="mail_plain_bb_attrib_name">'
								.htmlspecialchars($val[1]).'</span>';
							break;
						case 7:
							if (! trim($val[1])) {
								$str .= $val[1];
							} else {
								$str .= '<span class="mail_plain_bb_attrib_val">'
									.strtr(htmlspecialchars($val[1]), $chars)
									.'</span>';
							}
							break;
						default:
							$str .= $val[1];
					}
				}
				$str .= '</span>';
			}
		}
		$str = nl2br($str);
		$str = str_replace('  ', '&nbsp;&nbsp;', $str);
		return '<code class="mail_plain_bb_code">'.$str.'</code>';
	}

	function get_html($elems = false, &$quotes = null) {
		if (! is_array($elems)) {
			$elems = $this -> tree;
		}
		$result = '';
		$lbr = 0;
		$rbr = 0;;
		foreach ($elems as $elem) {
			if ('text' == $elem['type']) {
				/**
				* comment this
				*/
				$elem['str'] = $this -> insert_smiles($elem['str']);
				for ($i = 0; $i < $rbr; ++$i) {
					$elem['str'] = ltrim($elem['str']);
					if ('<br />' == substr($elem['str'], 0, 6)) {
						$elem['str'] = substr_replace($elem['str'], '', 0, 6);
					}
				}
				$result .= $elem['str'];
			} else {
				$class_vars = get_class_vars($this -> tags[$elem['name']]);
				$lbr = $class_vars['lbr'];
				$rbr = $class_vars['rbr'];
				for ($i=0; $i < $lbr; ++$i) {
					$result = rtrim($result);
					if ('<br />' == substr($result, -6)) {
						$result = substr_replace($result, '', -6, 6);
					}
				}
				$handler = $this -> tags[$elem['name']];
				if (class_exists($handler)) {
					$tag = new $handler;
					$tag -> tag = $elem['name'];
					$tag -> attrib = $elem['attrib'];
					$tag -> tags = $this -> tags;
					$tag -> mnemonics = $this -> mnemonics;
					$tag -> autolinks = $this -> autolinks;
					$tag -> tree = $elem['val'];
					$result .= $tag -> get_html($quotes);                    
				} else {
					$result .= mail_plain_bbcode::get_html($elem['val'], $quotes);
				}
			}
		}
		return $result;
	}
	
	function get_quotes(&$quotes)
	{
		$quotes[] = "*";
	}
}

// Класс для тегов [a], [anchor] и [url]
// fixed
class mail_plain_bb_a extends mail_plain_bbcode {
	var $ends = array(
		'*','align','center','h1','h2','h3','hr','justify','left','list','php',
		'quote','right','table','td','th','tr'
	);
	var $children = array(
		'abbr','acronym','b','bbcode','code','color','font','i','img','nobb',
		's','size','strike','sub','sup','tt','u'
	);
	function get_html() {
		$text = '';
		foreach ($this -> tree as $val) {
			if ('text' == $val['type']) { $text .= $val['str']; }
		}
		$href = '';
		if (isset($this -> attrib['url'])) {
			$href = $this -> attrib['url'];
		}
		if (! $href && isset($this -> attrib['a'])) {
			$href = $this -> attrib['a'];
		}
		if (! $href && isset($this -> attrib['href'])) {
			$href = $this -> attrib['href'];
		}
		if (! $href && ! isset($this -> attrib['anchor'])) { $href = $text; }
		
		$protocols = array(
			'http://',  'https://',  'ftp://',  'file://',  'mailto:',
			'#',        '/',         '?',       './',       '../'
		);
		$is_http = false;
		foreach ($protocols as $val) {
			if ($val == substr($href, 0, strlen($val))) {
				$is_http = true;
				break;
			}
		}
		if ($href && ! $is_http) { $href = 'http://'.$href; }
		
		
		return $href;
		/*
		$attr = 'class="bb"';
		if ($href) {
			$attr .= ' href="'.htmlspecialchars($href).'"';
		}
		if (isset($this -> attrib['title'])) {
			$title = $this -> attrib['title'];
			$attr .= ' title="'.htmlspecialchars($title).'"';
		}
		$id = '';
		if (isset($this -> attrib['name'])) {
			$id = $this -> attrib['name'];
		}
		if (isset($this -> attrib['id'])) {
			$id = $this -> attrib['id'];
		}
		if (isset($this -> attrib['anchor'])) {
			$id = $this -> attrib['anchor'];
			if (! $id) { $id = $text; }
		}
		if ($id) {
			if ($id{0} < 'A' || $id{0} > 'z') { $id = 'bb'.$id; }
			$attr .= ' id="'.htmlspecialchars($id).'"';
		}
		if (isset($this -> attrib['target'])) {
			$target = $this -> attrib['target'];
			$attr .= ' target="'.htmlspecialchars($target).'"';
		}
		return '<a '.$attr.'>'.parent::get_html($this -> tree).'</a>';
		*/
	}
}

// Класс для тега [abbr]
// fixed
class mail_plain_bb_abbr extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		$attrib = 'class="bb"';
		if ($this -> attrib['abbr']) {
			$attrib .= ' title="'.htmlspecialchars($this -> attrib['abbr']).'"';
		}
		//return '<abbr '.$attrib.'>'.parent::get_html($this -> tree).'</abbr>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [acronym]
// fixed 
class mail_plain_bb_acronym extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		$attrib = 'class="bb"';
		if ($this -> attrib['acronym']) {
			$attrib .= ' title="'.htmlspecialchars($this -> attrib['acronym'])
				.'"';
		}        
		//return '<acronym '.$attrib.'>'.parent::get_html($this -> tree).'</acronym>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [address]
// fixed 
class mail_plain_bb_address extends mail_plain_bbcode {
	var $rbr = 1;
	var $ends = array('*','tr','td','th');
	function get_html() {
		//return '<address class="bb">'.parent::get_html($this -> tree).'</address>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тегов [align], [center], [justify], [left] и [right]
// fixed
class mail_plain_bb_align extends mail_plain_bbcode {
	var $rbr = 1;
	var $ends = array('*','tr','td','th');
	function get_html() {
		$align = '';
		if (isset($this -> attrib['justify'])) { $align = 'justify'; }
		if (isset($this -> attrib['left'])) { $align = 'left'; }
		if (isset($this -> attrib['right'])) { $align = 'right'; }
		if (isset($this -> attrib['center'])) { $align = 'center'; }
		if (! $align && isset($this -> attrib['align'])) {
			switch (strtolower($this -> attrib['align'])) {
				case 'left':
					$align = 'left';
					break;
				case 'right':
					$align = 'right';
					break;
				case 'center':
					$align = 'center';
					break;
				case 'justify':
					$align = 'justify';
					break;
			}
		}
		//return '<div class="bb" align="'.$align.'">'.parent::get_html($this -> tree).'</div>';
		return parent::get_html($this -> tree);

	}
}

// Класс для тега [bbcode]
class mail_plain_bb_bbcode extends mail_plain_bbcode {
	var $ends = array();
	var $children = array();
	function get_html() {
		$str = '';
		foreach ($this -> tree as $item) {
			if ('item' == $item['type']) { continue; }
			$str .= $item['str'];
		}
		$bb = new mail_plain_bbcode();
		$bb -> tags = $this -> tags;
		$bb -> mnemonics = $this -> mnemonics;
		$bb -> autolinks = $this -> autolinks;
		$bb -> parse($str);
		return $bb -> highlight();
	}
}

// Класс для тега [caption]
// fixed
class mail_plain_bb_caption extends mail_plain_bbcode {
	var $ends = array('tr');
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','center','code','email',
		'font','google','i','nobb','s','size','strike','sub','sup','tt','u',
		'url'
	);
	function get_html() {
		//return '<caption class="bb">'.parent::get_html($this -> tree).'</caption>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [code]
// fixed 
class mail_plain_bb_code extends mail_plain_bbcode {
	var $ends = array();
	var $children = array();
	function get_html() {
		//$str = '<code class="bb">';
		$str = '';
		foreach ($this -> tree as $item) {
			if ('item' == $item['type']) { continue; }
			$str .= htmlspecialchars($item['str']);
		}
		return $str;
		/*
		$str .= '</code>';
		return str_replace('  ', '&nbsp;&nbsp;', nl2br($str));
		*/
	}
}

// Класс для тега [color]
// fixed 
class mail_plain_bb_color extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		$color = htmlspecialchars($this -> attrib['color']);
		//return '<font color="'.$color.'">'.parent::get_html($this -> tree).'</font>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тегов [s] и [strike]
// fixed  
class mail_plain_bb_del extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<del>'.parent::get_html($this -> tree).'</del>';
		return parent::get_html($this -> tree); 
	}
}

// Класс для тега [email]
// fixed 
class mail_plain_bb_email extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'abbr','acronym','b','bbcode','code','color','email','font','i','img',
		'nobb','s','size','strike','sub','sup','tt','u'
	);
	function get_html() {
		$attr = ' class="mail_plain_bb_email"';
		$href = $this -> attrib['email'];
		if (! $href) {
			foreach ($this -> tree as $text) {
				if ('text' == $text['type']) { $href .= $text['str']; }
			}
		}
		$protocols = array('mailto:');
		$is_http = false;
		foreach ($protocols as $val) {
			if ($val == substr($href,0,strlen($val))) {
				$is_http = true;
				break;
			}
		}
		
		return $href;

		/*
		if (! $is_http) { $href = 'mailto:'.$href; }
		if ($href) { $attr .= ' href="'.htmlspecialchars($href).'"'; }
		$title = isset($this -> attrib['title']) ? $this -> attrib['title'] : '';
		if ($title) { $attr .= ' title="'.htmlspecialchars($title).'"'; }
		$name = isset($this -> attrib['name']) ? $this -> attrib['name'] : '';
		if ($name) { $attr .= ' name="'.htmlspecialchars($name).'"'; }
		$target = isset($this -> attrib['target']) ? $this -> attrib['target'] : '';
		if ($target) { $attr .= ' target="'.htmlspecialchars($target).'"'; }
		return '<a'.$attr.'>'.parent::get_html($this -> tree).'</a>';
		*/
	}
}

// Класс для тега [font]
// fixed 
class mail_plain_bb_font extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','font','google','i','img','nobb','s','size','strike','sub','sup',
		'tt','u','url'
	);
	function get_html() {
		$face = $this -> attrib['font'];
		$attr = ' face="'.htmlspecialchars($face).'"';
		$color = isset($this -> attrib['color']) ? $this -> attrib['color'] : '';
		if ($color) { $attr .= ' color="'.htmlspecialchars($color).'"'; }
		$size = isset($this -> attrib['size']) ? $this -> attrib['size'] : '';
		if ($size) { $attr .= ' size="'.htmlspecialchars($size).'"'; }
		//return '<font'.$attr.'>'.parent::get_html($this -> tree).'</font>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [google]
// fixed  
class mail_plain_bb_google extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'abbr','acronym','b','bbcode','code','color','font','i','img','nobb',
		's','size','strike','sub','sup','tt','u'
	);
	function get_html() {
		$attr = htmlspecialchars(urlencode($this -> attrib['google']));
		$attr = ' href="http://www.google.com/search?q='.$attr.'"';
		$title = isset($this -> attrib['title']) ? $this -> attrib['title'] : '';
		if ($title) { $attr .= ' title="'.htmlspecialchars($title).'"'; }
		$name = isset($this -> attrib['name']) ? $this -> attrib['name'] : '';
		if ($name) { $attr .= ' name="'.htmlspecialchars($name).'"'; }
		$target = isset($this -> attrib['target']) ? $this -> attrib['target'] : '';
		if ($target) { $attr .= ' target="'.htmlspecialchars($target).'"'; }
		//return '<a class="mail_plain_bb_google" '.$attr.'>'.parent::get_html($this -> tree).'</a>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [h1]
// fixed 
class mail_plain_bb_h1 extends mail_plain_bbcode {
	var $lbr = 1;
	var $rbr = 2;
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		$attr = ' class="bb"';
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		//return '<h1'.$attr.'>'.parent::get_html($this -> tree).'</h1>';
		return parent::get_html($this -> tree); 
	}
}

// Класс для тега [h2]
// fixed 
class mail_plain_bb_h2 extends mail_plain_bbcode {
	var $lbr = 1;
	var $rbr = 2;
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','sub','strike','sup','tt',
		'u','url'
	);
	function get_html() {
		$attr = ' class="bb"';
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		//return '<h2'.$attr.'>'.parent::get_html($this -> tree).'</h2>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [h3]
// fixed 
class mail_plain_bb_h3 extends mail_plain_bbcode {
	var $lbr = 1;
	var $rbr = 2;
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		$attr = ' class="bb"';
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		//return '<h3'.$attr.'>'.parent::get_html($this -> tree).'</h3>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [hr]
// fixed 
class mail_plain_bb_hr extends mail_plain_bbcode {
	var $is_close = true;
	var $rbr = 1;
	var $ends = array();
	var $children = array();
	function get_html() {
		//return '<hr class="bb" />';
		return "\n";
	}
}

// Класс для тега [i]
// fixed 
class mail_plain_bb_i extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<i>'.parent::get_html($this -> tree).'</i>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [img]
// fixed
class mail_plain_bb_img extends mail_plain_bbcode {
	var $ends = array();
	var $children = array();
	function get_html() {
		$attr = 'alt=""';
		if (isset($this -> attrib['width'])) {
			$width = (int) $this -> attrib['width'];
			$attr .= $width ? ' width="'.$width.'"' : '';
		}
		if (isset($this -> attrib['height'])) {
			$height = (int) $this -> attrib['height'];
			$attr .= $height ? ' height="'.$height.'"' : '';
		}
		if (isset($this -> attrib['border'])) {
			$border = (int) $this -> attrib['border'];
			$attr .= ' border="'.$border.'"';
		}
		$src = '';
		foreach ($this -> tree as $text) {
			if ('text' == $text['type']) { $src .= $text['str']; }
		}
		$src = htmlentities($src, ENT_QUOTES);
		$src = str_replace('.', '&#'.ord('.').';', $src);
		$src = str_replace(':', '&#'.ord(':').';', $src);
		$src = str_replace('(', '&#'.ord('(').';', $src);
		$src = str_replace(')', '&#'.ord(')').';', $src);
		//return '<img src="'.$src.'" '.$attr.' />';
		return $src;
	}
}

// Класс для тега [*]
// fixed 
class mail_plain_bb_li extends mail_plain_bbcode {
	var $ends = array('*','tr','td','th');
	function get_html() {
		$attrib = 'class="bb"';
		if ('' !== $this -> attrib['*']) {
			$this -> attrib['*'] = (int) $this -> attrib['*'];
			$attrib .= ' value="'.$this -> attrib['*'].'"';
		}
		//return '<li '.$attrib.'>'.parent::get_html($this -> tree).'</li>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [list]
// fixed 
class mail_plain_bb_list extends mail_plain_bbcode {
	var $lbr = 0;
	var $rbr = 2;
	var $ends = array();
	var $children = array('*');
	function get_html() {
		$attr = ' class="bb"';
		$list_attr = strtolower($this -> attrib['list']);
		switch ($list_attr) {
			case '1':
				$tag_name = 'ol';
				$attr .= ' type="1"';
				break;
			case 'a':
				$tag_name = 'ol';
				$attr .= ' type="a"';
				break;
			default:
				$tag_name = 'ul';
		}
		$str = '<'.$tag_name.$attr.'>';
		foreach ($this -> tree as $key => $item) {
			if ('text' == $item['type']) { unset($this -> tree[$key]); }
		}
		$str .= parent::get_html($this -> tree).'</'.$tag_name.'>';
		//return $str;
		
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [nobb]  
class mail_plain_bb_nobb extends mail_plain_bbcode {
	var $ends = array();
	var $children = array();
	function get_html() {
		$str = '';
		foreach ($this -> tree as $item) {
			if ('text' == $item['type']) {
				$str .= nl2br(htmlspecialchars($item['str']));
			}
		}
		$str = str_replace('  ', '&nbsp;&nbsp;', $str);
		return $str;
	}
}

// Класс для тега [php]
// fixed 
class mail_plain_bb_php extends mail_plain_bbcode {
	var $ends = array();
	var $children = array();
	function get_html() {
		$str = '';
		foreach ($this -> tree as $item) {
			if ('text' == $item['type']) { $str .= $item['str']; }
		}
		
		return $str;
		/*
		if (false !== strpos($str, '<?')) {
			$str = highlight_string($str, true);
		} else {
			$str = '<?php '.$str.' ?>';
			$str = highlight_string($str, true);
			$str = str_replace('&lt;?php ', '', $str);
			$str = str_replace('&lt;?php&nbsp;', '', $str);
			$str = str_replace('>?&gt;<', '><', $str);
		}
		$str = preg_replace("'<span [^>]*></span>'si", '', $str);
		return '<div class="php">'.$str.'</div>';
		*/
	}
}

// Класс для тега [quote]
// fixed  
class mail_plain_bb_quote extends mail_plain_bbcode {
	var $rbr = 1;
	var $ends = array();
	function get_html(&$quotes) {
		
		$tmpQuote = array();
		$tmpQuote['post'] = null;
		if ($author = htmlspecialchars($this -> attrib['quote'])) {
			$post = new Warecorp_DiscussionServer_Post($author);
			if ( $post->getId() !== null ) $tmpQuote['post'] = $post;
		}
		$tmpQuote['text'] = parent::get_html($this -> tree, $quotes); 
		$quotes[] = $tmpQuote;

		if ($author = htmlspecialchars($this -> attrib['quote'])) {
			$post = new Warecorp_DiscussionServer_Post($author);
			if ( $post->getId() !== null ) {
				$post->setAuthor(new Warecorp_User('id', $post->getAuthorId()));
				
				$defaultTimezone = date_default_timezone_get();
				date_default_timezone_set('UTC');
				$date = new Zend_Date($post->getCreated(), Zend_Date::ISO_8601);
				date_default_timezone_set($defaultTimezone);
				
				$strHeader = "";
				$strHeader .= ' Subject : RE:'.$post->getTopic()->getSubject().''."\n";
				$strHeader .= ' From : '.$post->getAuthor()->getFirstname().' '.$post->getAuthor()->getLastname().' [mailto:'.$post->getAuthor()->getEmail().']'."\n";
				$strHeader .= ' Reply-To : '.$post->getTopic()->getDiscussion()->getTitle().' [mailto:'.$post->getTopic()->getDiscussion()->getFullEmail().']'."\n";
				$strHeader .= ' Date : '.$date->get(Zend_Date::RFC_2822)."\n";
				$strHeader .= "\n";
				
				$tmpQuote['text'] = $strHeader.$tmpQuote['text'];

				$lines = split("\n", $tmpQuote['text']);
				if ( sizeof($lines) != 0 ) {
					foreach ( $lines as &$line ) $line = '>'.$line;
				}
				$lines = join("\n", $lines);
				return $lines."\n\n\n";
			} else {      
				//return $tmpQuote['text']."\n";         
				
				$strHeader = "";
				$strHeader = "------------ QUOTE ------------\n";
				
				$lines = split("\n", $tmpQuote['text']);
				if ( sizeof($lines) != 0 ) {
					foreach ( $lines as &$line ) $line = ''.$line;
				}
				$lines = join("\n", $lines);
				$lines = $strHeader.$lines;
				$lines .= "\n-------------------------------";
				return $lines."\n";
			}
		} else {
			//return $tmpQuote['text']."\n";
			
			$strHeader = "";
			$strHeader = "------------ QUOTE ------------\n";
			
			$lines = split("\n", $tmpQuote['text']);
			if ( sizeof($lines) != 0 ) {
				foreach ( $lines as &$line ) $line = ''.$line;
			}
			$lines = join("\n", $lines);
			$lines = $strHeader.$lines;
			$lines .= "\n-------------------------------";
			return $lines."\n";
		}
		return $return;
	}
}

// Класс для тега [size]
// fixed 
class mail_plain_bb_size extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url','quote'
	);
	function get_html() {
		$sign = '';
		if (strlen($this -> attrib['size'])) {
			$sign = $this -> attrib['size']{0};
		}
		if ('+' != $sign) { $sign = ''; }
		$size = (int) $this -> attrib['size'];
		if (7 < $size) {
			$size = 7;
			$sign = '';
		}
		if (-6 > $size) {
			$size = '-6';
			$sign = '';
		}
		if (0 == $size) {
			$size = 3;
		}
		$size = $sign.$size;
		//return '<font size="'.$size.'">'.parent::get_html($this -> tree).'</font>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [b]
// fixed 
class mail_plain_bb_strong extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<strong>'.parent::get_html($this -> tree).'</strong>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [sub]
// fixed 
class mail_plain_bb_sub extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<sub>'.parent::get_html($this -> tree).'</sub>';
		return parent::get_html($this -> tree); 
	}
}

// Класс для тега [sup]
// fixed
class mail_plain_bb_sup extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<sup>'.parent::get_html($this -> tree).'</sup>';
		return parent::get_html($this -> tree);
	}
}

// Класс для тега [table]
class mail_plain_bb_table extends mail_plain_bbcode {
	var $rbr = 1;
	var $ends = array('table');
	var $children = array('tr','caption');
	function get_html() {
		$attr = ' class="bb"';
		$border = isset($this -> attrib['border'])
			? (int) $this -> attrib['border']
			: null;
		if (null !== $border) { $attr .= ' border="'.$border.'"'; }
		$width = isset($this -> attrib['width']) ? $this -> attrib['width'] : '';
		if ($width) { $attr .= ' width="'.htmlspecialchars($width).'"'; }
		$cellspacing = isset($this -> attrib['cellspacing'])
			? (int) $this -> attrib['cellspacing']
			: null;
		if (null !== $cellspacing) { $attr .= ' cellspacing="'.$cellspacing.'"'; }
		$cellpadding = isset($this -> attrib['cellpadding'])
			? (int) $this -> attrib['cellpadding']
			: null;
		if (null !== $cellpadding) { $attr .= ' cellpadding="'.$cellpadding.'"'; }
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		$str = '<table'.$attr.'>';
		foreach ($this -> tree as $key => $item) {
			if ('text' == $item['type']) { unset($this -> tree[$key]); }
		}
		$str .= parent::get_html($this -> tree).'</table>';
		return $str;
	}
}

// Класс для тега [td]
class mail_plain_bb_td extends mail_plain_bbcode {
	var $ends = array('td','th','tr');
	function get_html() {
		$attr = 'class="bb"';
		$width = isset($this -> attrib['width']) ? $this -> attrib['width'] : '';
		if ($width) { $attr .= ' width="'.htmlspecialchars($width).'"'; }
		$height = isset($this -> attrib['height']) ? $this -> attrib['height'] : '';
		if ($height) { $attr .= ' height="'.htmlspecialchars($height).'"'; }
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		$valign = isset($this -> attrib['valign']) ? $this -> attrib['valign'] : '';
		if ($valign) { $attr .= ' valign="'.htmlspecialchars($valign).'"'; }
		if (isset($this -> attrib['colspan'])) {
			$colspan = (int) $this -> attrib['colspan'];
			if ($colspan) { $attr .= ' colspan="'.$colspan.'"'; }
		}
		if (isset($this -> attrib['rowspan'])) {
			$rowspan = (int) $this -> attrib['rowspan'];
			if ($rowspan) { $attr .= ' rowspan="'.$rowspan.'"'; }
		}
		return '<td '.$attr.'>'.parent::get_html($this -> tree).'</td>';
	}
}

// Класс для тега [th]
class mail_plain_bb_th extends mail_plain_bbcode {
	var $ends = array('td','th','tr');
	function get_html() {
		$attr = ' class="bb"';
		$width = isset($this -> attrib['width']) ? $this -> attrib['width'] : '';
		if ($width) { $attr .= ' width="'.htmlspecialchars($width).'"'; }
		$height = isset($this -> attrib['height']) ? $this -> attrib['height'] : '';
		if ($height) { $attr .= ' height="'.htmlspecialchars($height).'"'; }
		$align = isset($this -> attrib['align']) ? $this -> attrib['align'] : '';
		if ($align) { $attr .= ' align="'.htmlspecialchars($align).'"'; }
		$valign = isset($this -> attrib['valign']) ? $this -> attrib['valign'] : '';
		if ($valign) { $attr .= ' valign="'.htmlspecialchars($valign).'"'; }
		if (isset($this -> attrib['colspan'])) {
			$colspan = (int) $this -> attrib['colspan'];
			if ($colspan) { $attr .= ' colspan="'.$colspan.'"'; }
		}
		if (isset($this -> attrib['rowspan'])) {
			$rowspan = (int) $this -> attrib['rowspan'];
			if ($rowspan) { $attr .= ' rowspan="'.$rowspan.'"'; }
		}
		return '<th'.$attr.'>'.parent::get_html($this -> tree).'</th>';
	}
}

// Класс для тега [tr]
class mail_plain_bb_tr extends mail_plain_bbcode {
	var $ends = array('tr');
	var $children = array('td','th');
	function get_html() {
		$str = '<tr class="bb">';
		foreach ($this -> tree as $key => $item) {
			if ('text' == $item['type']) { unset($this -> tree[$key]); }
		}
		$str .= parent::get_html($this -> tree).'</tr>';
		return $str;
	}
}

// Класс для тега [tt]
class mail_plain_bb_tt extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr','justify',
		'left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'google','i','img','nobb','s','size','strike','sub','sup','tt','u','url'
	);
	function get_html() {
		return '<tt>'.parent::get_html($this -> tree).'</tt>';
	}
}

// Класс для тега [u]
// fixed
class mail_plain_bb_u extends mail_plain_bbcode {
	var $ends = array(
		'*','address','align','center','h1','h2','h3','hr',
		'justify','left','list','php','quote','right','table','td','th','tr'
	);
	var $children = array(
		'a','abbr','acronym','anchor','b','bbcode','code','color','email',
		'font','google','i','img','nobb','s','size','strike','sub','sup','tt',
		'u','url'
	);
	function get_html() {
		//return '<u>'.parent::get_html($this -> tree).'</u>';
		return parent::get_html($this -> tree);
	}
}

?>

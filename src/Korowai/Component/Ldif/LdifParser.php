<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

class LdifParser
{

    use LdifParserUtil;
    use PregWithExceptions;

//    /**
//     * @var string
//     */
//    protected $ldif;

    /**
     * @var Korowai\Component\Ldif\Ast\LdifFile The root of AST three produced by the parser.
     */
    protected $ast;

    /**
     * @var Korowai\Component\Ldif\LdifParseError[]
     */
    protected $errors;


    public function parse(string $ldif)
    {
        return $this->parseLdifContent($ldif);
    }

//    public function getLdif()
//    {
//        return $this->ldif;
//    }

    public function getAst()
    {
        return $this->ast;
    }

    protected function parseLdifContent(string $ldif)
    {
        return $this->parseLdifFile($ldif, new Ast\LdifContent(), [self, 'parseAttrvalRecord']);
    }

    protected function parseLdifChanges(string $ldif)
    {
        return $this->parseLdifFile($ldif, new Ast\LdifContent(), [self, 'parseChangeRecord']);
    }

    protected function parseLdifFile(string $ldif, Ast\LdifFile $ast, callable $recordParser)
    {
        //$this->ldif = $ldif;
        $this->errors = [];
        $this->ast = $ast;

        $pieces = self::splitLdifString($ldif);

        $i = 0;

        // accept initial comments and seps
        self::acceptAstPieces($pieces, $i, $ast);

        if(self::parseVersionSpec($pieces, $i, $ast, $err) === false) {
            if($err->getMessage() !== 'syntax error: expected "version:" token') {
                $this->errors[] = $err;
                return false;
            }
            // version-spec may be missing (is optional), and this is actually acceptable
        }

        $records = 0;
        while($i < count($pieces)) {
            self::acceptAstPieces($pieces, $i, $ast);
            if($i < count($pieces)) {
                if(call_user_func($recordParser, $pieces, $i, $ast, $err) === false) {
                    $this->errors[] = $err;
                    return false;
                }
                $records++;
            }
        }
        if(!$records) {
            $line = $pieces[$i-1]->getStartLine();
            $this->errors[] = new LdifParseError('syntax error: no records defined', $line);
        }
    }


    protected static function acceptAstPieces(array $pieces, int &$i, Ast\LdifFile $ast)
    {
        for(;$i < count($pieces) && is_a($pieces[$i], Ast\NodeInterface::class); $i++) {
            $ast->addChild($pieces[$i]);
        }
    }

    protected static function checkPieceIndex(array $pieces, int $i, LdifParseError &$err)
    {
        if($i >= count($pieces)) {
            $line = count($pieces) > 0 ? $pieces[count($pieces)-1]->getEndLine() : -1;
            $err = new LdifParseError('syntax error: end of file', $line);
            return false;
        } elseif($i < 0) {
            $err = new LdifParseError('internal error: index is negative', -1);
            return false;
        }
        return true;
    }

    protected static function parseVersionSpec(array $pieces, int &$i, Ast\LdifFile $ast, LdifParseError &$err)
    {
        if(self::checkPieceIndex($pieces, $i, $err)) {
            return false;
        }

        $p = $pieces[$i];

        if(!is_a($p, LogicalLine::class)) {
            $err = new LdifParseError('syntax error: expected logical line line', $p->getStartLine());
            return false;
        }

        $ll = $p->unfolded();
        if(!substr($ll, 0, 8) === 'version:') {
            $err = new LdifParseError('syntax error: expected "version:" token', $p->getStartLine());
            return false;
        }

        $value = trim(substr($ll, 8));

        $status = self::preg_match('/^\d+$/', $value, $matches);

        if($status === 0) {
            $prefix = self::preg_replace('/^(version:(?:\s|\r\n|\n)*).*/', '$1', $ll);
            $line = $p->getLineOf(strlen($prefix));
            $err = new LdifParseError('syntax error: invalid version number "'. $value . '"', $line);
            return false;
        }

        $version = int($matches[0]);
        $code = $p->getContent();
        $start = $p->getStartLine();
        $end = $p->getEndLine();

        $ast->addChild(new Ast\VersionSpec($version, $code, $start, $end));
        $i++;

        return true;
    }

    protected static function parseAttrvalRecord(array $pieces, int &$i, Ast\LdifFile $ast, LdifParseError &$err)
    {
//        if(self::checkPieceIndex($pieces, $i, $err)) {
//            return false;
//        }
//
//        $record = new Ast\LdifAttrvalRecord();
//
//        if(self::parseDnSpec($pieces, $i, $)

        $i++;

        return true;
    }

//    protected function parseChangeRecord()
//    {
//        return $this->parseDnSpec() && $this->parseChangeSpec();
//    }
}

// vim: syntax=php sw=4 ts=4 et:

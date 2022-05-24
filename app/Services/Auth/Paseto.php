<?php

namespace App\Services\Auth;

use DateTime;
use App\Contracts\HasJwt;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Rules\ValidAt;
use ParagonIE\Paseto\Rules\IssuedBy;
use ParagonIE\Paseto\Rules\NotExpired;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Protocol\Version4;
use ParagonIE\Paseto\Rules\ForAudience;
use ParagonIE\Paseto\ProtocolCollection;

class Paseto
{
	protected SymmetricKey $sharedKey;

	public function __construct(?string $secretKey = null)
	{
		$this->sharedKey = new SymmetricKey($secretKey ?? setting('jwt_secret_key'));
	}

	public function encodeToken(HasJwt $user, array $config = []): string
	{
		$builder = new Builder;

		return $builder
			->setKey($this->sharedKey)
			->setVersion(new Version4)
			->setPurpose(Purpose::local())
			->setIssuer(config('app.url'))
			->setAudience(config('app.url'))
			->setIssuedAt()
			->setNotBefore(new DateTime(formatTimestamp($config['valid_from'] ?? $user->getJwtValidFromTime())))
			->setExpiration(new DateTime(formatTimestamp($config['valid_until'] ?? $user->getJwtValidUntilTime())))
			->setJti($config['id'] ?? $user->getJwtId())
			->setClaims($config['claims'] ?? $user->getJwtCustomClaims())
			->toString();
	}

	public function decodeToken(string $token): JsonToken
	{
		$parser = new Parser;

		return $parser
			->setKey($this->sharedKey)
			->setAllowedVersions(ProtocolCollection::v4())
			->setPurpose(Purpose::local())
			->addRule(new IssuedBy(config('app.url')))
			->addRule(new ForAudience(config('app.url')))
			->addRule(new ValidAt)
			->addRule(new NotExpired)
			->parse($token);
	}
}

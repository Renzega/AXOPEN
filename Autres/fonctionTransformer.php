	function UpdateStatNumber($number)
	{
		if($number >= 0 && $number <= 999)
		{
			return $number;
		}
		else if($number >= 1000 && $number <= 999999999999)
		{
			$notation = null;
			if($number >= 1000 && $number <= 999999)
			{
				$notation = "K";
			}
			else if($number >= 1000000 && $number <= 999999999)
			{
				$notation = "M";
			}
			else if($number >= 1000000000 && $number <= 999999999999)
			{
				$notation = "Mi";
			}
			$tests = strlen($number);
			$requests = 0;
			$result = 0;
			while($tests != 0)
			{
				$tests--;
				$requests++;
				if($tests > 0)
				{
					if($requests >= 3)
					{
						$requests = 0;
						$result++;
					}
				}
			}
			$firstPacket = substr($number, 0, (-3*$result));
			$secondPacket = substr($number, strlen($firstPacket), 3);
			$secondPacketFirstCharacter = $secondPacket[0];
			$secondPacketRest = $secondPacket[1].$secondPacket[2];
			$newSecondPacket = substr_replace($secondPacket, $secondPacketFirstCharacter.".".$secondPacketRest, 0);
			$totalPacket = $firstPacket.$secondPacket;
			if(!($totalPacket >= 9951 && $totalPacket <= 9999 || $totalPacket >= 99951 && $totalPacket <= 99999 || $totalPacket >= 999951 && $totalPacket <= 999999))
			{
				if($secondPacketRest > 50)
				{
					$newSecondPacket = ceil($newSecondPacket);
				}
				else if($secondPacketRest == 50)
				{
					$newSecondPacket = substr_replace($newSecondPacket, $secondPacket[0].$secondPacket[1], 0);
				}
				else
				{
					$newSecondPacket = floor($newSecondPacket);
				}
				$totalPacket = substr_replace($number, $firstPacket.",".$newSecondPacket.$notation, 0);
			}
			else
			{
				if($totalPacket >= 9951 && $totalPacket <= 9999)
				{
					$totalPacket = 10000;
					$totalPacket = substr($totalPacket, 0, -3);
					$totalPacket = $totalPacket.$notation;
				}
				else if($totalPacket >= 99951 && $totalPacket <= 99999)
				{
					$totalPacket = 100000;
					$totalPacket = substr($totalPacket, 0, -3);
					$totalPacket = $totalPacket.$notation;
				}
				else if($totalPacket >= 999951 && $totalPacket <= 999999)
				{
					$totalPacket = 10;
					$totalPacket = substr($totalPacket, 0, -1);
					if($number >= 1000 && $number <= 999999)
					{
						$notation = "M";
					}
					else if($number >= 1000000 && $number <= 999999999)
					{
						$notation = "Mi";
					}
					else if($number >= 1000000000 && $number <= 999999999999)
					{
						$notation = "Bi";
					}
					$totalPacket = $totalPacket.$notation;
				}
			}
			echo $totalPacket;
		}
	}
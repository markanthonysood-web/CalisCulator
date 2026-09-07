<?php
// Handle form input
$expression = $_POST['expression'] ?? '';
$result = '';
$fortune = '';

// Mixed Fortunes Library (Combining Serious, Mystical, Funny, and Relatable)
$fortunesPool = [
    // Zero Outcomes
    "Zero! Your path today offers a complete fresh start and a clean slate! 🕯️",
    "Zero! Your motivation level today matches this exact result—time to rest! 😴",
    "Total Reset! The universe is handing you a completely blank canvas today. 🎨",
    
    // Negative Outcomes
    "Negative value! Energy flows outward too quickly—protect your resources today. 🛑",
    "Negative value! Warning: Your wallet may scream if you open any shopping apps today! 💸",
    "In the red! Take a step back and double-check your plans before moving forward. 📉",
    
    // Decimal Outcomes
    "Decimals! Mastery lies in the subtle nuances. Pay attention to the tiny details today. 🔍",
    "Decimals! Your life is currently 99.9% complete, but that last 0.1% is driving you crazy. 🧩",
    
    // Milestones & Lucky Numbers
    "Lucky Sevens! Alignment achieved—unexpected luck is heading directly your way! 🎰✨",
    "Angel Numbers ({VAL})! Cosmic synchronicities surround you. Drink water and trust the process! 🔮",
    "Millionaire Energy! You are thinking on a grand scale—a massive breakthrough is unfolding! 💰",
    "Millionaire Energy! You can now afford extra guacamole without checking your balance! 🥑",
    "High Voltage Output! You possess extraordinary momentum—channel it toward your main goal. ⚡",
    "High Voltage Output! You will briefly feel like a genius until you forget why you entered the room. 🧠",
    
    // General / Odd / Even Outcomes
    "Even Number! Harmony enters your day—emotional balance and peace will prevail. ☯️",
    "Even Number! Perfect balance achieved: Exactly half your day will be spent avoiding work! ☕",
    "Odd Number! Catalyst for change detected—a sudden shift will lead to unexpected growth. 🎢",
    "Odd Number! Expect an exciting plot twist, like finding a forgotten $20 bill in your pocket! 🧺",
    "Your current actions are laying a foundation that will yield results within three months. 🏛️",
    "Your luck today is like elevator Wi-Fi—unpredictable and dropping at the worst moments! 📶",
    "Subtle guidance is drawing you toward a decision you have been hesitating to make. 🌿",
    "Be cautious of people borrowing pens—they vanish faster than ghosts! 🖊️👻",
    "Opportunities are like buses: another one is coming, but you must be ready to step on! 🚌",
    "A peaceful week awaits you... as long as you mute the main group chat! 🤫"
];

function generateFortune($val) {
    global $fortunesPool;

    // Filter index ranges for specific numeric outcomes
    if ($val == 0) {
        $options = [0, 1, 2];
    } elseif ($val < 0) {
        $options = [3, 4, 5];
    } elseif (is_float($val) && floor($val) != $val) {
        $options = [6, 7];
    } elseif ($val == 7 || $val == 77 || $val == 777) {
        return "Lucky Sevens! Alignment achieved—unexpected luck is heading directly your way! 🎰✨";
    } elseif ($val >= 111 && $val <= 999 && $val % 111 == 0) {
        return "Angel Numbers ($val)! Cosmic synchronicities surround you—trust the process! 🔮✨";
    } elseif ($val >= 1000000) {
        $options = [10, 11];
    } elseif ($val >= 1000) {
        $options = [12, 13];
    } else {
        // Pick any random fortune from the remaining pool
        $randomText = $fortunesPool[array_rand($fortunesPool)];
        return str_replace('{VAL}', $val, $randomText);
    }

    $selectedIndex = $options[array_rand($options)];
    return str_replace('{VAL}', $val, $fortunesPool[$selectedIndex]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'C') {
        $expression = '';
        $result = '';
        $fortune = '';
    } elseif ($action === 'DEL') {
        $expression = substr($expression, 0, -1);
        $result = '';
        $fortune = '';
    } elseif ($action === '=') {
        if (!empty($expression)) {
            $cleanExpression = trim($expression);
            
            // Check for division by zero
            if (preg_match('/\/0(\.0*)?($|[\+\-\*\/])/', $cleanExpression)) {
                $result = "Error";
                $fortune = "Cannot divide by zero! Reality is ripping at the seams! 🌀";
            } else {
                try {
                    $evalResult = @eval("return $cleanExpression;");
                    
                    if ($evalResult !== false && $evalResult !== null) {
                        $result = number_format($evalResult, strlen(substr(strrchr($evalResult, "."), 1)) > 0 ? 2 : 0);
                        $fortune = generateFortune($evalResult);
                    } else {
                        $result = "Error";
                        $fortune = "Invalid equation! The crystal ball cannot parse this math! 🔮";
                    }
                } catch (Throwable $e) {
                    $result = "Error";
                    $fortune = '';
                }
            }
        }
    } else {
        $expression .= $action;
        $result = '';
        $fortune = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fortune Teller Calculator 🔮</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .calculator-card {
            background-color: #1e293b;
            width: 100%;
            max-width: 350px;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            border: 1px solid #334155;
        }

        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #64748b;
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
            padding: 0 0.2rem;
            font-weight: 500;
        }

        .header-title {
            color: #f8fafc;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding: 0 0.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .display-screen {
            background-color: #0f172a;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            text-align: right;
            margin-bottom: 1.2rem;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #334155;
        }

        .expression {
            color: #94a3b8;
            font-size: 1rem;
            letter-spacing: 0.5px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .result {
            color: #f8fafc;
            font-size: 2.2rem;
            font-weight: 600;
            margin: 0.2rem 0;
            overflow-x: auto;
            white-space: nowrap;
        }

        .fortune-inline {
            text-align: left;
            border-top: 1px solid #1e293b;
            padding-top: 0.6rem;
            margin-top: 0.4rem;
            color: #cbd5e1;
            font-size: 0.8rem;
            line-height: 1.35;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: normal;
            hyphens: none;
        }

        .fortune-label {
            color: #38bdf8;
            font-weight: 600;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }

        .keypad {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .btn {
            background-color: #334155;
            color: #f8fafc;
            border: none;
            height: 52px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.05s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn:hover { background-color: #475569; }
        .btn:active { transform: scale(0.96); }

        .btn-grey {
            background-color: #475569;
            color: #f8fafc;
        }
        .btn-grey:hover { background-color: #64748b; }

        .btn-accent {
            background-color: #0284c7;
            color: #ffffff;
        }
        .btn-accent:hover { background-color: #0369a1; }

        .btn-equal {
            background-color: #38bdf8;
            color: #0f172a;
            font-weight: 600;
        }
        .btn-equal:hover { background-color: #7dd3fc; }
    </style>
</head>
<body>

<div class="calculator-card">
    <div class="status-bar">
        <span>9:41</span>
        <span>🔮</span>
    </div>

    <div class="header-title">
        <span>Calculator</span>
    </div>

    <div class="display-screen">
        <div>
            <div class="expression"><?= htmlspecialchars($expression ?: '0') ?></div>
            <div class="result"><?= $result !== '' ? '= ' . htmlspecialchars($result) : '' ?></div>
        </div>

        <?php if (!empty($fortune)): ?>
            <div class="fortune-inline">
                <div class="fortune-label">Fortune Prediction 🔮</div>
                "<?= htmlspecialchars($fortune) ?>"
            </div>
        <?php endif; ?>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="expression" value="<?= htmlspecialchars($expression) ?>">
        
        <div class="keypad">
            <!-- Row 1 -->
            <button type="submit" name="action" value="C" class="btn btn-grey">C</button>
            <button type="submit" name="action" value="DEL" class="btn btn-grey">⌫</button>
            <button type="submit" name="action" value="/" class="btn btn-accent">/</button>
            <button type="submit" name="action" value="*" class="btn btn-accent">*</button>

            <!-- Row 2 -->
            <button type="submit" name="action" value="7" class="btn">7</button>
            <button type="submit" name="action" value="8" class="btn">8</button>
            <button type="submit" name="action" value="9" class="btn">9</button>
            <button type="submit" name="action" value="-" class="btn btn-accent">-</button>

            <!-- Row 3 -->
            <button type="submit" name="action" value="4" class="btn">4</button>
            <button type="submit" name="action" value="5" class="btn">5</button>
            <button type="submit" name="action" value="6" class="btn">6</button>
            <button type="submit" name="action" value="+" class="btn btn-accent">+</button>

            <!-- Row 4 -->
            <button type="submit" name="action" value="1" class="btn">1</button>
            <button type="submit" name="action" value="2" class="btn">2</button>
            <button type="submit" name="action" value="3" class="btn">3</button>
            <button type="submit" name="action" value="=" class="btn btn-equal" style="grid-row: span 2; height: 100%;">=</button>

            <!-- Row 5 -->
            <button type="submit" name="action" value="0" class="btn" style="grid-column: span 2;">0</button>
            <button type="submit" name="action" value="." class="btn">.</button>
        </div>
    </form>
</div>

</body>
</html>
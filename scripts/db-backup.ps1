param(
    [string]$ProjectRoot = "C:\xampp\htdocs\clothing_rental",
    [string]$MysqlBin = "C:\xampp\mysql\bin",
    [int]$KeepDays = 14
)

$ErrorActionPreference = 'Stop'

$envFile = Join-Path $ProjectRoot '.env'
if (-not (Test-Path $envFile)) {
    throw ".env not found at $envFile"
}

# Parse .env values used for database backup.
$envMap = @{}
Get-Content $envFile | ForEach-Object {
    if ($_ -match '^(DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=(.*)$') {
        $envMap[$matches[1]] = $matches[2]
    }
}

if (($envMap['DB_CONNECTION'] -ne 'mysql') -or (-not $envMap['DB_DATABASE'])) {
    throw 'Only MySQL backups are supported by this script.'
}

$dbHost = if ($envMap['DB_HOST']) { $envMap['DB_HOST'] } else { '127.0.0.1' }
$port = if ($envMap['DB_PORT']) { $envMap['DB_PORT'] } else { '3306' }
$dbName = $envMap['DB_DATABASE']
$user = if ($envMap['DB_USERNAME']) { $envMap['DB_USERNAME'] } else { 'root' }
$pass = $envMap['DB_PASSWORD']

$backupDir = Join-Path $ProjectRoot 'database\backups'
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

$timestamp = Get-Date -Format 'yyyyMMdd_HHmmss'
$sqlFile = Join-Path $backupDir ("${dbName}_$timestamp.sql")
$zipFile = Join-Path $backupDir ("${dbName}_$timestamp.zip")

$mysqldumpExe = Join-Path $MysqlBin 'mysqldump.exe'
if (-not (Test-Path $mysqldumpExe)) {
    throw "mysqldump not found at $mysqldumpExe"
}

$mysqladminExe = Join-Path $MysqlBin 'mysqladmin.exe'
$mysqldExe = Join-Path $MysqlBin 'mysqld.exe'
if (-not (Test-Path $mysqladminExe)) {
    throw "mysqladmin not found at $mysqladminExe"
}

function Test-MySqlAlive {
    param(
        [string]$AdminExe,
        [string]$DbHost,
        [string]$DbPort,
        [string]$DbUser,
        [string]$DbPass
    )

    $args = @("--host=$DbHost", "--port=$DbPort", "--user=$DbUser", 'ping')
    if ($DbPass -ne $null -and $DbPass -ne '') {
        $args = @("--password=$DbPass") + $args
    }

    & $AdminExe @args | Out-Null
    return ($LASTEXITCODE -eq 0)
}

if (-not (Test-MySqlAlive -AdminExe $mysqladminExe -DbHost $dbHost -DbPort $port -DbUser $user -DbPass $pass)) {
    try {
        Start-Service mysql -ErrorAction Stop
    }
    catch {
        if (-not (Test-Path $mysqldExe)) {
            throw "mysqld not found at $mysqldExe"
        }
        Start-Process -FilePath $mysqldExe -ArgumentList "--defaults-file=C:\xampp\mysql\bin\my.ini", '--console' -WindowStyle Hidden | Out-Null
    }

    $maxWaitSeconds = 20
    $isUp = $false
    for ($i = 0; $i -lt $maxWaitSeconds; $i++) {
        Start-Sleep -Seconds 1
        if (Test-MySqlAlive -AdminExe $mysqladminExe -DbHost $dbHost -DbPort $port -DbUser $user -DbPass $pass) {
            $isUp = $true
            break
        }
    }

    if (-not $isUp) {
        throw 'MySQL is not reachable for backup.'
    }
}

$dumpArgs = @(
    "--host=$dbHost",
    "--port=$port",
    "--user=$user",
    '--routines',
    '--triggers',
    '--events',
    '--single-transaction',
    '--quick',
    '--databases',
    $dbName,
    "--result-file=$sqlFile"
)

if ($pass -ne $null -and $pass -ne '') {
    $dumpArgs = @("--password=$pass") + $dumpArgs
}

& $mysqldumpExe @dumpArgs
if ($LASTEXITCODE -ne 0) {
    throw "mysqldump failed with exit code $LASTEXITCODE"
}

Compress-Archive -Path $sqlFile -DestinationPath $zipFile -Force
Remove-Item $sqlFile -Force

# Retention: delete backup files older than KeepDays.
$cutoff = (Get-Date).AddDays(-$KeepDays)
Get-ChildItem $backupDir -File | Where-Object { $_.LastWriteTime -lt $cutoff } | Remove-Item -Force

Write-Output "Backup created: $zipFile"

[Setup]
AppName=Aplikasi SPJ ARKAS by SPEMTO
AppVersion=1.0
AppPublisher=Tim IT SMP Muhammadiyah Tonjong
AppPublisherURL=https://smpmuhtonjong.sch.id
AppSupportURL=https://smpmuhtonjong.sch.id
AppContact=085185033377 / smpmuhitonjong@gmail.com
AppCopyright=© Tim IT SMP Muhammadiyah Tonjong

; === BYPASS ADMIN ACCESS & AUTO DUAL-ARCH (32-BIT / 64-BIT) ===
PrivilegesRequired=lowest
ArchitecturesInstallIn64BitMode=x64compatible
DefaultDirName={userpf}\Spemto_SpjArkas
DefaultGroupName=Aplikasi SPJ ARKAS by SPEMTO

OutputDir=OutputInstaller
OutputBaseFilename=Setup_SPJ_ARKAS_SPEMTO_v1.0
Compression=lzma2/ultra64
SolidCompression=yes
; SetupIconFile=C:\project\SpjArkas\public\favicon.ico
UninstallDisplayIcon={app}\public\favicon.ico

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked
Name: "autostart"; Description: "Jalankan SPJ ARKAS saat Windows Startup (Rekomendasi)"; GroupDescription: "Pengaturan Startup:"

[Files]
; Menyalin seluruh project (termasuk php portable dan database default)
Source: "C:\project\SpjArkas\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "OutputInstaller\*, .git\*, .github\*, node_modules\*"

[InstallDelete]
; Pembersihan total file cache, session, view, dan config lama sebelum menyalin file baru
Type: filesandordirs; Name: "{app}\storage\framework\views\*"
Type: filesandordirs; Name: "{app}\storage\framework\cache\*"
Type: filesandordirs; Name: "{app}\storage\framework\sessions\*"
Type: filesandordirs; Name: "{app}\bootstrap\cache\*"
Type: files; Name: "{app}\.env"

[Icons]
; Shortcut Aplikasi Utama (Mengeksekusi runner VBS dengan Ikon favicon.ico)
Name: "{group}\Aplikasi SPJ ARKAS"; Filename: "wscript.exe"; Parameters: """{app}\run_background.vbs"""; WorkingDir: "{app}"; IconFilename: "{app}\public\favicon.ico"
Name: "{group}\Buka di Browser Default"; Filename: "http://127.0.0.1:8000"; IconFilename: "{app}\public\favicon.ico"
Name: "{group}\{cm:UninstallProgram,Aplikasi SPJ ARKAS}"; Filename: "{uninstallexe}"

; Shortcut Desktop & Startup Windows
Name: "{autodesktop}\Aplikasi SPJ ARKAS"; Filename: "wscript.exe"; Parameters: """{app}\run_background.vbs"""; WorkingDir: "{app}"; IconFilename: "{app}\public\favicon.ico"; Tasks: desktopicon
Name: "{userstartup}\SpjArkasRunner"; Filename: "wscript.exe"; Parameters: """{app}\run_background.vbs"""; WorkingDir: "{app}"; IconFilename: "{app}\public\favicon.ico"; Tasks: autostart

[Run]
; 1. Matikan seluruh instance PHP dan WScript lama
Filename: "{cmd}"; Parameters: "/c taskkill /F /IM php.exe /T >nul 2>&1"; Flags: runhidden; StatusMsg: "Menutup sesi PHP lama di latar belakang..."
Filename: "{cmd}"; Parameters: "/c taskkill /F /IM wscript.exe /T >nul 2>&1"; Flags: runhidden; StatusMsg: "Menutup runner script lama..."

; 2. Timpa paksa .env lama dengan .env.example fresh
Filename: "{cmd}"; Parameters: "/c copy /Y ""{app}\.env.example"" ""{app}\.env"""; Flags: runhidden; StatusMsg: "Menimpa file konfigurasi lama..."

; 3. Purge cache konfigurasi Laravel lama
Filename: "{app}\php\php.exe"; Parameters: "artisan config:clear"; WorkingDir: "{app}"; Flags: runhidden skipifdoesntexist; StatusMsg: "Membersihkan cache konfigurasi..."

; 4. Generate APP_KEY Laravel baru
Filename: "{app}\php\php.exe"; Parameters: "artisan key:generate --force"; WorkingDir: "{app}"; Flags: runhidden skipifdoesntexist; StatusMsg: "Membuat kunci keamanan aplikasi baru..."

; 5. Link Storage Laravel agar file media/logo/ttd dapat diakses
Filename: "{app}\php\php.exe"; Parameters: "artisan storage:link"; WorkingDir: "{app}"; Flags: runhidden skipifdoesntexist; StatusMsg: "Menghubungkan folder media..."

; 6. Migrate Database SQLite beserta seeding default
Filename: "{app}\php\php.exe"; Parameters: "artisan migrate --seed --force"; WorkingDir: "{app}"; Flags: runhidden skipifdoesntexist; StatusMsg: "Menyiapkan database & tabel SPJ ARKAS..."

; 7. Eksekusi server di background & buka browser
Filename: "wscript.exe"; Parameters: """{app}\run_background.vbs"""; WorkingDir: "{app}"; Description: "Jalankan Aplikasi SPJ ARKAS Sekarang"; Flags: postinstall runhidden

[UninstallRun]
; Hentikan proses latar belakang PHP dan WScript saat uninstall
Filename: "{cmd}"; Parameters: "/c taskkill /F /IM php.exe /T >nul 2>&1"; Flags: runhidden; RunOnceId: "StopPhpProcessOnUninstall"
Filename: "{cmd}"; Parameters: "/c taskkill /F /IM wscript.exe /T >nul 2>&1"; Flags: runhidden; RunOnceId: "StopWscriptProcessOnUninstall"

[UninstallDelete]
; Pembersihan total folder instalasi saat uninstall
Type: filesandordirs; Name: "{app}\storage\framework\views\*"
Type: filesandordirs; Name: "{app}\storage\framework\cache\*"
Type: filesandordirs; Name: "{app}\storage\framework\sessions\*"
Type: filesandordirs; Name: "{app}\bootstrap\cache\*"
Type: filesandordirs; Name: "{app}\*"
Type: dirifempty; Name: "{app}"

[Code]
var
  DetectedBrowserPath: String;
  IsAppModeSupported: Boolean;

function DetectBestBrowser(): String;
var
  Path: String;
begin
  if RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\chrome.exe', '', Path) or
     RegQueryStringValue(HKEY_CURRENT_USER, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\chrome.exe', '', Path) then
  begin
    if FileExists(Path) then begin IsAppModeSupported := True; Result := Path; Exit; end;
  end;

  if FileExists(ExpandConstant('{commonpf64}\Google\Chrome\Application\chrome.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{commonpf64}\Google\Chrome\Application\chrome.exe'); Exit; end;
  if FileExists(ExpandConstant('{commonpf32}\Google\Chrome\Application\chrome.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{commonpf32}\Google\Chrome\Application\chrome.exe'); Exit; end;
  if FileExists(ExpandConstant('{userappdata}\Google\Chrome\Application\chrome.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{userappdata}\Google\Chrome\Application\chrome.exe'); Exit; end;
  if FileExists(ExpandConstant('{localappdata}\Google\Chrome\Application\chrome.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{localappdata}\Google\Chrome\Application\chrome.exe'); Exit; end;

  if RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\msedge.exe', '', Path) or
     RegQueryStringValue(HKEY_CURRENT_USER, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\msedge.exe', '', Path) then
  begin
    if FileExists(Path) then begin IsAppModeSupported := True; Result := Path; Exit; end;
  end;

  if FileExists(ExpandConstant('{commonpf32}\Microsoft\Edge\Application\msedge.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{commonpf32}\Microsoft\Edge\Application\msedge.exe'); Exit; end;
  if FileExists(ExpandConstant('{commonpf64}\Microsoft\Edge\Application\msedge.exe')) then begin IsAppModeSupported := True; Result := ExpandConstant('{commonpf64}\Microsoft\Edge\Application\msedge.exe'); Exit; end;

  if RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\firefox.exe', '', Path) or
     RegQueryStringValue(HKEY_CURRENT_USER, 'SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\firefox.exe', '', Path) then
  begin
    if FileExists(Path) then begin IsAppModeSupported := False; Result := Path; Exit; end;
  end;

  IsAppModeSupported := False;
  Result := 'http://127.0.0.1:8000';
end;

function InitializeSetup(): Boolean;
begin
  DetectedBrowserPath := DetectBestBrowser();
  Result := True;
end;

function GetBrowserPath(Param: String): String;
begin
  Result := DetectedBrowserPath;
end;

function GetBrowserParams(Param: String): String;
begin
  if IsAppModeSupported then
    Result := '--app=http://127.0.0.1:8000'
  else begin
    if DetectedBrowserPath = 'http://127.0.0.1:8000' then 
      Result := '' 
    else 
      Result := 'http://127.0.0.1:8000';
  end;
end;

procedure CurUninstallStepChanged(CurUninstallStep: TUninstallStep);
var
  AppDir: String;
  ResultCode: Integer;
begin
  if CurUninstallStep = usUninstall then
  begin
    Exec('taskkill.exe', '/F /IM php.exe /T', '', SW_HIDE, ewWaitUntilTerminated, ResultCode);
    Exec('taskkill.exe', '/F /IM wscript.exe /T', '', SW_HIDE, ewWaitUntilTerminated, ResultCode);
  end;

  if CurUninstallStep = usPostUninstall then
  begin
    AppDir := ExpandConstant('{app}');
    if DirExists(AppDir) then
    begin
      DelTree(AppDir, True, True, True);
    end;
  end;
end;
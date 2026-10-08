Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Path direktori aplikasi saat ini
strPath = fso.GetParentFolderName(WScript.ScriptFullName)

' 1. Hentikan instance PHP lama yang berjalan di port 8000
WshShell.Run "cmd /c taskkill /F /IM php.exe /T >nul 2>&1", 0, True

' 2. Jalankan start_server.bat di latar belakang (Hidden style = 0)
WshShell.Run """" & strPath & "\start_server.bat""", 0, False

' 3. Beri jeda 2 detik agar server Laravel siap
WScript.Sleep 2000

' 4. Deteksi Browser untuk mode App Chrome/Edge
Dim appUrl
appUrl = "http://127.0.0.1:8000"

Function FindBrowser()
    Dim paths, p
    paths = Array( _
        WshShell.ExpandEnvironmentStrings("%ProgramFiles%\Google\Chrome\Application\chrome.exe"), _
        WshShell.ExpandEnvironmentStrings("%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"), _
        WshShell.ExpandEnvironmentStrings("%LocalAppData%\Google\Chrome\Application\chrome.exe"), _
        WshShell.ExpandEnvironmentStrings("%ProgramFiles%\Microsoft\Edge\Application\msedge.exe"), _
        WshShell.ExpandEnvironmentStrings("%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe") _
    )
    For Each p In paths
        If fso.FileExists(p) Then
            FindBrowser = """" & p & """ --app=" & appUrl
            Exit Function
        End If
    Next
    FindBrowser = appUrl
End Function

' 5. Buka Browser
WshShell.Run FindBrowser(), 1, False
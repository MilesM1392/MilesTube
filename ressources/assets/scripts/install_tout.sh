```bash
#!/bin/bash

set -e

if [[ "$EUID" -ne 0 ]]; then
    echo "Lance le script avec sudo."
    exit 1
fi

echo "=== Mise à jour des dépôts ==="
apt update

echo "=== Installation de FFmpeg et FFprobe ==="
apt install -y ffmpeg

echo "=== Installation de yt-dlp ==="
curl -L \
    https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp \
    -o /usr/local/bin/yt-dlp

chmod +x /usr/local/bin/yt-dlp

echo "=== Installation de Deno ==="
export DENO_INSTALL="/opt/deno"

curl -fsSL https://deno.land/install.sh | sh

ln -sf /opt/deno/bin/deno /usr/local/bin/deno

echo "=== Vérification ==="

ffmpeg -version | head -n 1
ffprobe -version | head -n 1
yt-dlp --version
deno --version

echo "=== Installation terminée ! ==="
```
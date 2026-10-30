FROM ghcr.io/sil-org/php8:8.3

# Ubuntu's PHP package reports version 8.3.6 even with security patches backported, which
# roave/security-advisories rejects. Use the ondrej/php PPA to get current upstream releases.
RUN apt-get update \
 && apt-get install -y --no-install-recommends software-properties-common gpg-agent \
 && add-apt-repository -y ppa:ondrej/php \
 && apt-get update \
 && apt-get upgrade -y \
 && rm -rf /var/lib/apt/lists/*

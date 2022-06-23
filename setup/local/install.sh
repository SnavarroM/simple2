docker-compose up --build -d && \
echo "Please wait while service is up..." && \
sleep 5 && \
docker exec local_simple2_app bash /var/www/setup/local/start.sh

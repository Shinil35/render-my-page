FROM debian:buster

RUN apt-get update && apt-get install -y chromium chromium-driver python3 python3-pip
RUN pip3 install selenium requests redis

WORKDIR /app
COPY /src .

ENTRYPOINT ["python3", "-u", "run_bot.py"]
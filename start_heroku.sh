#!/bin/bash
heroku ps:scale web=1
heroku maintenance:off

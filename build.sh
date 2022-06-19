#!/usr/bin/env bash

##############################################################################
#                                                                            #
# Script to build the containers for the Performance Dashboard               #
#                                                                            #
##############################################################################

function usage() {
    echo "Usage: $0 <kind> <operaion>"
    echo ""
    echo " kind : the kind of deployment : prod | dev"
    echo " operation : the operation on the deployment : build | run | stop"
}

function run() {
    docker-compose up -d
}

function stop() {
    docker-compose down
}

function build () {
    if [[ $1 == "prod" ]] ; then
        cp -r ../../html .
    fi

    docker-compose build --no-cache

    if [[ $1 == "prod" ]] ; then
        rm -rf html
    fi
}

if [[ $# -ne 2 ]] ; then
    usage
    exit 1
fi

deployment=$1

if [[ $deployment != "dev" && $deployment != "prod" ]] ; then
    usage
    exit 2
fi

echo "Going to create $deployment deployment !"

cd infra/$deployment
if [[ $? -ne 0 ]] ; then
    echo "The script must run from the main repo. directory !"
    exit 3
fi

if [[ $2 == "run" ]] ; then
    run
fi

if [[ $2 == "stop" ]] ; then
    stop
fi

if [[ $2 == "build" ]] ; then
    build $deployment
fi

#!/usr/bin/env bash

declare -A models
models["user"]="UserBase"
models["method"]="MethodBase"
models["reset"]="ResetBase"
models["requests_by_ip"]="RequestsByIpBase"
models["password_change_log"]="PasswordChangeLogBase"

models["event_log"]="EventLogBase"

for i in "${!models[@]}"; do
    CMD="./yii gii/model --tableName=$i --modelClass=${models[$i]} --enableI18N=1 --overwrite=1 --interactive=0 --ns=\common\models"
    echo "${CMD}"
    $CMD
done

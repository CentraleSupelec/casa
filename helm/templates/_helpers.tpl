{{/*
Expand the name of the chart.
*/}}
{{- define "casa.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
If release name contains chart name it will be used as a full name.
*/}}
{{- define "casa.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "casa.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Common labels
*/}}
{{- define "casa.labels" -}}
helm.sh/chart: {{ include "casa.chart" . }}
{{ include "casa.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{- define "casa.commonLabels" -}}
helm.sh/chart: {{ include "casa.chart" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{/*
Selector labels
*/}}
{{- define "casa.selectorLabels" -}}
app.kubernetes.io/name: {{ include "casa.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}

{{- define "casa.mjmlSelectorLabels" -}}
app.kubernetes.io/name: {{ include "casa.name" . }}-mjml
app.kubernetes.io/instance: {{ .Release.Name }}-mjml
app.kubernetes.io/component: mjml
app.kubernetes.io/part-of: {{ include "casa.name" . }}
{{- end }}

{{- define "casa.clamavSelectorLabels" -}}
app.kubernetes.io/name: {{ include "casa.name" . }}-clamav
app.kubernetes.io/instance: {{ .Release.Name }}-clamav
app.kubernetes.io/component: clamav
app.kubernetes.io/part-of: {{ include "casa.name" . }}
{{- end }}

{{/*
Create the name of the service account to use
*/}}
{{- define "casa.serviceAccountName" -}}
{{- if .Values.serviceAccount.create }}
{{- default (include "casa.fullname" .) .Values.serviceAccount.name }}
{{- else }}
{{- default "default" .Values.serviceAccount.name }}
{{- end }}
{{- end }}

{{- define "casa.volumes" -}}
- name: env
  secret:
    defaultMode: 420
    secretName: {{ include "casa.fullname" . }}-env
{{- end }}

{{- define "casa.volume_mounts" -}}
- mountPath: /app/.env.local
  name: env
  subPath: .env
{{- end }}

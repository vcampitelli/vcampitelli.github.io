FROM jekyll/jekyll:3.8

WORKDIR /app

# Dependências
COPY ["Gemfile", "Gemfile.lock", "./"]
RUN mkdir /srv/cv && \
    chown -R jekyll:jekyll /srv/cv && \
    bundle install --jobs 4

# Resto dos arquivos
COPY . ./cv-src

CMD bundle exec jekyll serve -w -H 0.0.0.0

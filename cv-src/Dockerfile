FROM jekyll/jekyll:4

WORKDIR /app

# Dependências
RUN gem install bundler -v 2.6.9
COPY ["Gemfile", "Gemfile.lock", "./"]
RUN mkdir /app/cv && \
    chown -R jekyll:jekyll /app && \
    bundle install

# Resto dos arquivos
COPY . ./cv-src

CMD ["bundle", "exec", "jekyll", "serve", "-w", "-H", "0.0.0.0"]

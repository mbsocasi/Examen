package com.cursos.cursos;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.openfeign.EnableFeignClients;

@EnableFeignClients // Habilita el soporte para Feign
@SpringBootApplication
public class CursosApplication {
	public static void main(String[] args) {
		SpringApplication.run(CursosApplication.class, args);
	}
}

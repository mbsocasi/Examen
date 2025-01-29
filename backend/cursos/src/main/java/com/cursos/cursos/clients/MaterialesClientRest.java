package com.cursos.cursos.clients;

import com.cursos.cursos.models.Materiales;
import org.springframework.cloud.openfeign.FeignClient;
import org.springframework.web.bind.annotation.GetMapping;

import java.util.List;

@FeignClient(name = "micro-materiales", url = "http://localhost:8003/api/materiales")
public interface MaterialesClientRest {

    @GetMapping
    List<Materiales> findAll();
}

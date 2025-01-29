package com.cursos.cursos.services;

import com.cursos.cursos.clients.MaterialesClientRest;
import com.cursos.cursos.models.Materiales;
import com.cursos.cursos.models.entities.Cursos;
import com.cursos.cursos.models.entities.CurosMateriales;
import com.cursos.cursos.repositories.CursosRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class CursosServiceImpl implements CursosService {

    @Autowired
    private CursosRepository repository;

    @Autowired
    private MaterialesClientRest materialesClient;

    @Override
    public List<Cursos> findAll() {
        return (List<Cursos>) repository.findAll();
    }

    @Override
    public Optional<Cursos> findById(Long id) {
        return repository.findById(id);
    }

    @Override
    public Cursos save(Cursos curso) {
        return repository.save(curso);
    }

    @Override
    public void deleteById(Long id) {
        repository.deleteById(id);
    }

    @Override
    public Optional<Cursos> addMaterial(Long cursoId, Materiales material) {
        return repository.findById(cursoId).map(curso -> {
            CurosMateriales cursoMaterial = new CurosMateriales(material.getId());
            if (!curso.getCursoMateriales().contains(cursoMaterial)) {
                curso.getCursoMateriales().add(cursoMaterial);
                repository.save(curso);
            }
            return curso;
        });
    }

    @Override
    public Optional<Cursos> removeMaterial(Long cursoId, Long materialId) {
        return repository.findById(cursoId).map(curso -> {
            curso.getCursoMateriales().removeIf(cm -> cm.getMaterialId().equals(materialId));
            repository.save(curso);
            return curso;
        });
    }

    @Override
    public List<Materiales> getAllMaterials() {
        return materialesClient.findAll();
    }
}

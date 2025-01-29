package com.materiales.materiales.services;

import com.materiales.materiales.models.entities.Materiales;
import com.materiales.materiales.repositories.MaterialesRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;
import java.util.List;
import java.util.Optional;

@Service
public class MaterialesServicelmpl implements MaterialesService {
    @Autowired
    private MaterialesRepository repository;

    @Override
    public List<Materiales> findAll() {
        return (List<Materiales>) repository.findAll();
    }

    @Override
    public Optional<Materiales> findById(Long id) {
        return repository.findById(id);
    }

    @Override
    public Materiales save(Materiales materiales) {
        if (materiales.getId() == null) { // Ensure it's a new record
            materiales.setCreado(LocalDateTime.now());
        }
        return repository.save(materiales);
    }

    @Override
    public void deleteById(Long id) {
        repository.deleteById(id);
    }

    @Override
    public Materiales update(Long id, Materiales material) {
        return repository.findById(id).map(existingMaterial -> {
            existingMaterial.setNombre(material.getNombre());
            existingMaterial.setTipo(material.getTipo());
            existingMaterial.setDescripcion(material.getDescripcion());
            return repository.save(existingMaterial);
        }).orElseThrow(() -> new RuntimeException("Material not found with id: " + id));
    }
}

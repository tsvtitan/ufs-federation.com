package ufsic.scheme.thymeleaf;

import java.util.HashMap;
import java.util.Map;
import java.util.Set;
import org.thymeleaf.Arguments;
import org.thymeleaf.Configuration;
import org.thymeleaf.context.IProcessingContext;
import org.thymeleaf.dom.NestableAttributeHolderNode;
import org.thymeleaf.dom.Node;
import org.thymeleaf.processor.AttributeNameProcessorMatcher;
import org.thymeleaf.processor.IProcessor;
import org.thymeleaf.processor.IProcessorMatcher;
import org.thymeleaf.processor.ProcessorMatchingContext;
import org.thymeleaf.processor.ProcessorResult;
import org.thymeleaf.standard.StandardDialect;
import org.thymeleaf.standard.expression.IStandardVariableExpressionEvaluator;
import org.thymeleaf.standard.expression.StandardExpressionExecutionContext;
import ufsic.scheme.Scheme;

public class SchemeStandardDialect extends StandardDialect implements /*IProcessor, IProcessorMatcher ,*/IStandardVariableExpressionEvaluator {

  private Scheme scheme = null;
  
  public SchemeStandardDialect(Scheme scheme) {
    super();
    this.scheme = scheme;
    //setVariableExpressionEvaluator(this);
  }
/*
  @Override
  public Set<IProcessor> getProcessors() {
    
    final Set<IProcessor> processors = super.getProcessors();
    processors.add(this);
    return processors;
  }

  @Override
  public ProcessorResult process(Arguments arguments, ProcessorMatchingContext processorMatchingContext, Node node) {
    
    Map<String,Object> vars = new HashMap<>();
    vars.put("testLocal","---Hello Local---");
    ProcessorResult.setLocalVariables(vars);
    ProcessorResult.setProcessTextNodes(true);
    return ProcessorResult.ok();
  }

  @Override
  public int compareTo(IProcessor o) {
    return 0;
  }

  @Override
  public IProcessorMatcher<? extends Node> getMatcher() {
    return this;
  }

  @Override
  public boolean matches(Node node, ProcessorMatchingContext context) {
    return true;
  }

  @Override
  public Class appliesTo() {
    return Node.class;
  } 
  
  */

  @Override
  public Object evaluate(Configuration configuration, IProcessingContext processingContext, String expression, StandardExpressionExecutionContext expContext, boolean useSelectionAsRoot) {
    
    return String.format("%s = %s = %s",expression,processingContext,expContext);
  }
  
  
}
